<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Attendance;

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
        $attendance = Attendance::with('user', 'breakTimes')->findOrFail($id);

        return view('admin.attendance.show', compact('attendance'));
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::with('breakTimes')->findOrFail($id);

        $request->validate([
            'clock_in' => 'required',
            'clock_out' => 'required',
            'remark' => 'required',
        ], [
            'remark.required' => '備考を記入してください',
        ]);

        $workDate = Carbon::parse($attendance->work_date);

        $clockIn = $workDate->copy()->setTimeFromTimeString($request->clock_in);
        $clockOut = $workDate->copy()->setTimeFromTimeString($request->clock_out);

        if ($clockIn->gt($clockOut)) {
            return back()->withErrors([
                'clock_time' => '出勤時間もしくは退勤時間が不適切な値です'
            ])->withInput();
        }

        if ($request->has('break_start')) {
            foreach ($request->break_start as $index => $start) {

                if (!$start) continue;

                $breakStart = $workDate->copy()->setTimeFromTimeString($start);

                if ($breakStart->lt($clockIn) || $breakStart->gt($clockOut)) {
                    return back()->withErrors([
                        'break_time' => '休憩時間が不適切な値です'
                    ])->withInput();
                }

                if (!empty($request->break_end[$index])) {

                    $breakEnd = $workDate->copy()->setTimeFromTimeString($request->break_end[$index]);

                    if ($breakEnd->lt($breakStart)) {
                        return back()->withErrors([
                            'break_time' => '休憩時間が不適切な値です'
                        ])->withInput();
                    }

                    if ($breakEnd->gt($clockOut)) {
                        return back()->withErrors([
                            'break_time' => '休憩時間もしくは退勤時間が不適切な値です'
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

        foreach ($attendance->breakTimes as $index => $break) {

            $start = $request->break_start[$index] ?? null;
            $end = $request->break_end[$index] ?? null;

            $break->update([
                'break_start' => $start ? $workDate->copy()->setTimeFromTimeString($start) : null,
                'break_end' => $end ? $workDate->copy()->setTimeFromTimeString($end) : null,
            ]);
        }

        return redirect()->route('admin.attendance.show', $attendance->id);
    }
}
