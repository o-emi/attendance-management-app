<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Attendance;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\AttendanceUpdateRequest;

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

    public function update(AttendanceUpdateRequest $request, $id)
    {
        $attendance = Attendance::with('breakTimes')->findOrFail($id);

        if ($attendance->status === '承認待ち') {
            return back()->withErrors([
                'attendance' => '承認待ちのため修正はできません。'
            ]);
        }

        $workDate = Carbon::parse($attendance->work_date);

        $clockIn = $workDate->copy()->setTimeFromTimeString($request->clock_in);
        $clockOut = $workDate->copy()->setTimeFromTimeString($request->clock_out);

        $attendance->update([
            'clock_in' => $clockIn,
            'clock_out' => $clockOut,
            'remark' => $request->remark,
        ]);

        $attendance->breakTimes()->delete();

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

        return redirect()
            ->route('admin.attendance.show', $attendance->id)
            ->with('message', '勤怠情報を更新しました');
    }
}