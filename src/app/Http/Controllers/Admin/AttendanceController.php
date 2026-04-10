<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Attendance;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());

        $users = User::with(['attendances' => function ($query) use ($date) {
            $query->whereDate('work_date', $date)
                ->with('breakTimes');
        }])->get();

        return view('admin.attendance.list', compact('users', 'date'));
    }

    public function show($id)
    {
        $attendance = Attendance::with('user', 'breakTimes', 'correctionRequests')->findOrFail($id);

        $latestRequest = $attendance->correctionRequests()
            ->with('breakTimes')
            ->latest()
            ->first();

        return view('admin.attendance.show', compact('attendance', 'latestRequest'));
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::with('breakTimes')->findOrFail($id);

        if ($attendance->status === '承認待ち') {
            return back()->withErrors([
                'attendance' => '承認待ちのため修正はできません。'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'clock_in' => 'required',
            'clock_out' => 'required',
            'remark' => 'required',
        ], [
            'remark.required' => '備考を記入してください',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $workDate = Carbon::parse($attendance->work_date);

        $clockIn = $workDate->copy()->setTimeFromTimeString($request->clock_in);
        $clockOut = $workDate->copy()->setTimeFromTimeString($request->clock_out);

        if ($clockIn->gte($clockOut)) {
            return back()->withErrors([
                'clock_in' => '出勤時間もしくは退勤時間が不適切な値です',
                // 'clock_out' => '出勤時間もしくは退勤時間が不適切な値です',
            ])->withInput();
        }

        if ($request->has('break_start')) {
            foreach ($request->break_start as $index => $start) {
                if (!$start) {
                    continue;
                }

                $breakStart = $workDate->copy()->setTimeFromTimeString($start);
                $end = $request->break_end[$index] ?? null;

                if ($breakStart->lt($clockIn) || $breakStart->gt($clockOut)) {
                    return back()->withErrors([
                        "break_start.$index" => '休憩時間が不適切な値です',
                    ])->withInput();
                }

                if ($end) {
                    $breakEnd = $workDate->copy()->setTimeFromTimeString($end);

                    if ($breakStart->gte($breakEnd)) {
                        return back()->withErrors([
                            "break_start.$index" => '休憩時間が不適切な値です',
                            // "break_end.$index" => '休憩時間が不適切な値です',
                        ])->withInput();
                    }

                    if ($breakEnd->gt($clockOut)) {
                        return back()->withErrors([
                            "break_end.$index" => '休憩時間もしくは退勤時間が不適切な値です',
                        ])->withInput();
                    }
                }
            }
        }

        $attendance->update([
            'clock_in' => $clockIn,
            'clock_out' => $clockOut,
            'remark' => $request->remark,
        ]);

        $attendance->breakTimes()->delete();

        if ($request->has('break_start')) {
            foreach ($request->break_start as $index => $start) {
                $end = $request->break_end[$index] ?? null;

                if (!$start && !$end) {
                    continue;
                }

                $attendance->breakTimes()->create([
                    'break_start' => $start
                        ? $workDate->copy()->setTimeFromTimeString($start)
                        : null,
                    'break_end' => $end
                        ? $workDate->copy()->setTimeFromTimeString($end)
                        : null,
                ]);
            }
        }

        return redirect()
            ->route('admin.attendance.show', $attendance->id)
            ->with('message', '勤怠情報を更新しました');
    }
}