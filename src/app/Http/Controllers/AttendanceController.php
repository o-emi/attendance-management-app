<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function index()
    {
        $today = Attendance::where('user_id', auth()->id())
            ->whereDate('work_date', today())
            ->first();

        if (!$today) {
            $status = '勤務外';

        } elseif ($today->clock_in && !$today->clock_out) {
            $status = '出勤中';

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

        // 出勤処理
        if (!$today->clock_in) {

            $today->update([
                'clock_in' => now(),
                'status' => '出勤中'
            ]);

            return back();
        }

        // 退勤処理
        if (!$today->clock_out) {

            $today->update([
                'clock_out' => now(),
                'status' => '退勤済'
            ]);

            return back()->with('message', 'お疲れ様でした。');
        }

        return back();
    }
}