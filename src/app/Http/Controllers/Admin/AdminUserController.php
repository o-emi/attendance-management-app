<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'user')->get();

        return view('admin.staff.index', compact('users'));
    }

    public function showAttendance(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $currentMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $month = Carbon::createFromFormat('Y-m', $currentMonth);

        $startOfMonth = $month->copy()->startOfMonth();
        $endOfMonth = $month->copy()->endOfMonth();

        $attendances = Attendance::with('breakTimes')
            ->where('user_id', $id)
            ->whereBetween('work_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->get()
            ->map(function ($attendance) {
                $breakTotalSeconds = $attendance->breakTimes->sum(function ($break) {
                    if ($break->break_start && $break->break_end) {
                        return Carbon::parse($break->break_end)->diffInSeconds(
                            Carbon::parse($break->break_start)
                        );
                    }

                    return 0;
                });

                $workTotalSeconds = 0;

                if ($attendance->clock_in && $attendance->clock_out) {
                    $workTotalSeconds = Carbon::parse($attendance->clock_out)->diffInSeconds(
                        Carbon::parse($attendance->clock_in)
                    ) - $breakTotalSeconds;
                }

                $attendance->break_total_seconds = $breakTotalSeconds;
                $attendance->work_total_seconds = $workTotalSeconds;

                return $attendance;
            })
            ->keyBy(function ($attendance) {
                return Carbon::parse($attendance->work_date)->format('Y-m-d');
            });

        $period = CarbonPeriod::create($startOfMonth, $endOfMonth);

        return view('admin.staff.attendance', compact(
            'user',
            'attendances',
            'currentMonth',
            'period'
        ));
    }

    public function exportCsv(Request $request, $id): StreamedResponse
    {
        $user = User::findOrFail($id);

        $currentMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $month = Carbon::createFromFormat('Y-m', $currentMonth);

        $startOfMonth = $month->copy()->startOfMonth();
        $endOfMonth = $month->copy()->endOfMonth();

        $attendances = Attendance::with('breakTimes')
            ->where('user_id', $id)
            ->whereBetween('work_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->get()
            ->map(function ($attendance) {
                $breakTotalSeconds = $attendance->breakTimes->sum(function ($break) {
                    if ($break->break_start && $break->break_end) {
                        return Carbon::parse($break->break_end)->diffInSeconds(
                            Carbon::parse($break->break_start)
                        );
                    }

                    return 0;
                });

                $workTotalSeconds = 0;

                if ($attendance->clock_in && $attendance->clock_out) {
                    $workTotalSeconds = Carbon::parse($attendance->clock_out)->diffInSeconds(
                        Carbon::parse($attendance->clock_in)
                    ) - $breakTotalSeconds;
                }

                $attendance->break_total_seconds = $breakTotalSeconds;
                $attendance->work_total_seconds = $workTotalSeconds;

                return $attendance;
            })
            ->keyBy(function ($attendance) {
                return Carbon::parse($attendance->work_date)->format('Y-m-d');
            });

        $period = CarbonPeriod::create($startOfMonth, $endOfMonth);

        $fileName = $user->name . '_' . $month->format('Y_m') . '_attendance.csv';

        return response()->streamDownload(function () use ($period, $attendances, $user) {
            $handle = fopen('php://output', 'w');

            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, ['ユーザー名', $user->name]);
            fputcsv($handle, []);
            fputcsv($handle, ['日付', '出勤', '退勤', '休憩', '合計']);

            foreach ($period as $date) {
                $attendance = $attendances[$date->format('Y-m-d')] ?? null;

                fputcsv($handle, [
                    $date->format('Y/m/d'),
                    $attendance?->clock_in ? Carbon::parse($attendance->clock_in)->format('H:i') : '',
                    $attendance?->clock_out ? Carbon::parse($attendance->clock_out)->format('H:i') : '',
                    $attendance?->break_total_seconds ? gmdate('H:i', $attendance->break_total_seconds) : '',
                    $attendance?->work_total_seconds ? gmdate('H:i', $attendance->work_total_seconds) : '',
                ]);
            }

            fclose($handle);
        }, $fileName);
    }
}
