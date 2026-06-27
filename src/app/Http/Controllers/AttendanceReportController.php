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

        // 月次推移
        $monthlyReports = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);

            $startDate = $month->copy()->startOfMonth();

            $endDate = $month->copy()->endOfMonth();

            $attendances = Attendance::with('breakTimes')
                ->where('user_id', $user->id)
                ->whereBetween('work_date', [$startDate, $endDate])
                    ->get();

            $monthlyWorkSeconds = 0;
            $monthlyOvertimeSeconds = 0;

            foreach ($attendances as $attendance) {
                // ①出勤～退勤の秒数
                $workSeconds = Carbon::parse($attendance->clock_in)
                    ->diffInSeconds(Carbon::parse($attendance->clock_out));

                // ②休憩秒数を入れる箱
                $breakSeconds = 0;

                // ③休憩を1件ずつ足す
                foreach ($attendance->breakTimes as $breakTime) {
                    $breakSeconds += Carbon::parse($breakTime->break_start)
                        ->diffInSeconds(Carbon::parse($breakTime->break_end));
                }

                // ④実労働時間秒数を月合計に足す
                $monthlyWorkSeconds += $workSeconds - $breakSeconds;

                $standardEndTime = Carbon::parse($attendance->work_date)
                    ->setTime(18, 0, 0);

                $clockOut = Carbon::parse($attendance->clock_out);

                if ($clockOut->gt($standardEndTime)) {
                    $monthlyOvertimeSeconds += $standardEndTime->diffInSeconds($clockOut);
                }
            }

            $monthlyWorkHours = (int) floor($monthlyWorkSeconds / 3600);
            $remainingMonthlyWorkSeconds = $monthlyWorkSeconds % 3600;
            $monthlyWorkMinutes = (int) floor($remainingMonthlyWorkSeconds / 60);

            $monthlyOvertimeHours = (int) floor($monthlyOvertimeSeconds / 3600);
            $remainingMonthlyOvertimeSeconds = $monthlyOvertimeSeconds % 3600;
            $monthlyOvertimeMinutes = (int) floor($remainingMonthlyOvertimeSeconds / 60);

            $monthlyReports[] = [
                'month' => $month->format('Y/m'),
                'work_hours' => $monthlyWorkHours,
                'work_minutes' => $monthlyWorkMinutes,
                'overtime_hours' => $monthlyOvertimeHours,
                'overtime_minutes' => $monthlyOvertimeMinutes,
            ];
        }

        return view('attendance.report', compact(
            'totalHours',
            'totalMinutes',
            'totalOvertimeHours',
            'totalOvertimeMinutes',
            'averageWorkHours',
            'averageWorkMinutes',
            'monthlyReports'
        ));
    }
}
