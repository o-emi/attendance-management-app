<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\BreakTime;

class AttendanceController extends Controller
{
    public function index()
    {
        $today = Attendance::with('breakTimes')
            ->where('user_id', auth()->id())
            ->whereDate('work_date', today())
            ->first();

        if (!$today) {
            $status = '勤務外';

        } elseif ($today->clock_in && !$today->clock_out) {

            $onBreak = $today->breakTimes()
                ->whereNull('break_end')
                ->exists();

            $status = $onBreak ? '休憩中' : '出勤中';

        } else {
            $status = '退勤済';
        }

        return view('attendance.index', compact('status'));
    }


    public function punch()
    {
        $today = Attendance::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'work_date' => today()
            ]
        );

        if (!$today->clock_in) {

            $today->update([
                'clock_in' => now(),
                'status' => '出勤中'
            ]);

            return back();
        }

        if (!$today->clock_out) {

            $today->update([
                'clock_out' => now(),
                'status' => '退勤済'
            ]);

            return back()->with('message', 'お疲れ様でした。');
        }

        return back();
    }

    public function breakStart()
    {
        $today = Attendance::where('user_id', auth()->id())
            ->whereDate('work_date', today())
            ->firstOrFail();

        BreakTime::create([
            'attendance_id' => $today->id,
            'break_start' => now(),
        ]);

        return back();
    }

    public function breakEnd()
    {
        $today = Attendance::where('user_id', auth()->id())
            ->whereDate('work_date', today())
            ->firstOrFail();

        $break = $today->breakTimes()
            ->whereNull('break_end')
            ->latest()
            ->first();

        if ($break) {
            $break->update([
                'break_end' => now(),
            ]);
        }

        return back();
    }

    public function list()
    {
        $attendances = auth()->user()->attendances()
            ->orderBy('work_date', 'desc')
            ->get();

        return view('attendance.list', compact('attendances'));
    }
}