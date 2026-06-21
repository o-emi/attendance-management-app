<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceReportController;
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

        $totalWorkSeconds = 0;

        foreach ($attendances as $attendance) {
            $workSeconds = Carbon::parse($attendance->clock_in)
                ->diffInSeconds(Carbon::parse($attendance->clock_out));

            $breakSeconds = 0;

            foreach ($attendance->breakTimes as $breakTime) {
                $breakSeconds += Carbon::parse($breakTime->break_start)
                    ->diffInSeconds(Carbon::parse($breakTime->break_end));
            }

            $actualWorkSeconds = $workSeconds - $breakSeconds;

            $totalWorkSeconds += $actualWorkSeconds;

            $totalHours = (int) floor($totalWorkSeconds / 3600);

            $remainingSeconds = $totalWorkSeconds % 3600;

            $totalMinutes = (int) floor($remainingSeconds / 60);

        }

        return view('attendance.report', compact(
            'totalHours',
            'totalMinutes'
        ));
    }
}
