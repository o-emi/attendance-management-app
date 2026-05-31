<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\BreakTime;
use App\Models\CorrectionRequest;
use App\Http\Requests\AttendanceUpdateRequest;

class AttendanceController extends Controller
{
    public function index()
    {
        $today = Attendance::with('breakTimes')
            ->where('user_id', auth()->id())
            ->whereDate('work_date', today())
            ->first();

        if (!$today || !$today->clock_in) {
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
            ['user_id' => auth()->id(), 'work_date' => today()]
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

    public function list(Request $request)
    {
        $user = auth()->user();

        $month = $request->month
            ? Carbon::parse($request->month)
            : Carbon::now();

        $start = $month->copy()->startOfMonth();
        $end = $month->copy()->endOfMonth();

        $period = CarbonPeriod::create($start, $end);

        $attendances = Attendance::with('breakTimes')
            ->where('user_id', $user->id)
            ->whereBetween('work_date', [$start, $end])
            ->get()
            ->keyBy(function ($item) {
                return $item->work_date->format('Y-m-d');
            });

        return view('attendance.list', compact(
            'attendances',
            'month',
            'period'
        ));
    }

    public function show($id)
    {
        $attendance = Attendance::with('breakTimes', 'correctionRequests')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $latestRequest = $attendance->correctionRequests()
            ->with('breakTimes')
            ->where('status', 'pending')
            ->latest('id')
            ->first();

        return view('attendance.show', compact('attendance', 'latestRequest'));
    }

    public function request(AttendanceUpdateRequest $request, $id)
    {
        $attendance = Attendance::where('user_id', auth()->id())
        ->findOrFail($id);

        $correctionRequest = CorrectionRequest::create([
            'user_id' => auth()->id(),
            'attendance_id' => $attendance->id,
            'start_time' => $request->clock_in,
            'end_time' => $request->clock_out,
            'note' => $request->remark,
            'status' => 'pending',
        ]);

        foreach ($request->break_start as $index => $start) {
            $end = $request->break_end[$index] ?? null;

            if (!$start && !$end) continue;

            $correctionRequest->breakTimes()->create([
                'break_start' => $start,
                'break_end' => $end,
            ]);
        }

        $attendance->update([
            'status' => 'pending',
        ]);

        return redirect()->back()->with('message', '修正申請を送信しました');
    }

    public function requestList(Request $request)
    {
        $status = $request->query('status', 'pending');

        $requests = CorrectionRequest::with(['user', 'attendance'])
            ->where('user_id', auth()->id())
            ->where('status', $status)
            ->latest()
            ->get();

        return view('attendance.request.list', compact('requests'));
    }
}