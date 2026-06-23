<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceReportController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $startDate = Carbon::now()->subMonths(5)->startOfMonth();

        $endDate = Carbon::now()->endOfMonth();

        $attendances = Attendance::with('breakTimes')
            ->where('user_id', $user->id)
            ->whereBetween('work_date', [
                $startDate,
                $endDate
            ])
            ->get();

        // 総労働時間
        $totalWorkSeconds = 0;

        // 総残業時間
        $totalOvertimeSeconds = 0;

        foreach ($attendances as $attendance) {

            // １日の労働時間
            $workSeconds = Carbon::parse($attendance->clock_in)
                ->diffInSeconds(Carbon::parse($attendance->clock_out));

            // １日の休憩時間
            $breakSeconds = 0;

            foreach ($attendance->breakTimes as $breakTime) {
                $breakSeconds += Carbon::parse($breakTime->break_start)
                    ->diffInSeconds(Carbon::parse($breakTime->break_end));
            }

            // １日の実労働時間
            $actualWorkSeconds = $workSeconds - $breakSeconds;

            $totalWorkSeconds += $actualWorkSeconds;

            // １日の残業時間
            $standardEndTime = Carbon::parse($attendance->work_date)
                ->setTime(18, 0, 0);

            $clockOut = Carbon::parse($attendance->clock_out);

            if ($clockOut->gt($standardEndTime)) {
                $totalOvertimeSeconds += $standardEndTime->diffInSeconds($clockOut);
            }

        }
        // 総労働時間を時間・分に変換
        $totalHours = (int) floor($totalWorkSeconds / 3600);
        $remainingSeconds = $totalWorkSeconds % 3600;
        $totalMinutes = (int) floor($remainingSeconds / 60);

        // 総残業時間を時間・分に変換
        $totalOvertimeHours = (int) floor($totalOvertimeSeconds / 3600);
        $remainingOvertimeSeconds = $totalOvertimeSeconds % 3600;
        $totalOvertimeMinutes = (int) floor($remainingOvertimeSeconds / 60);

        // 平均労働時間
        $attendanceCount = $attendances->count();

        $averageWorkSeconds = $attendanceCount > 0
            ? $totalWorkSeconds / $attendanceCount
            : 0;

        $averageWorkHours = (int) floor($averageWorkSeconds / 3600);
        $remainingAverageSeconds = $averageWorkSeconds % 3600;
        $averageWorkMinutes = (int) floor($remainingAverageSeconds / 60);

        return view('attendance.report', compact(
            'totalHours',
            'totalMinutes',
            'totalOvertimeHours',
            'totalOvertimeMinutes',
            'averageWorkHours',
            'averageWorkMinutes'
        ));
    }
}
