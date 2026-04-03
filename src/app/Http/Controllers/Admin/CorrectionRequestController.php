<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CorrectionRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CorrectionRequestController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');

        $requests = CorrectionRequest::with('user')
            ->when($status === 'approved', function ($query) {
                $query->where('status', 'approved');
            }, function ($query) {
                $query->where('status', 'pending');
            })
            ->latest()
            ->get();

        return view('admin.correction_requests.index', compact('requests', 'status'));
    }

    public function show($id)
    {
        $correctionRequest = CorrectionRequest::with([
            'attendance.breakTimes',
            'breakTimes',
            'user'
        ])->findOrFail($id);

        return view('admin.correction_requests.show', compact('correctionRequest'));
    }

    public function approve($id)
    {
        $correctionRequest = CorrectionRequest::with('breakTimes', 'attendance.breakTimes')
            ->findOrFail($id);

        DB::transaction(function () use ($correctionRequest) {

            $attendance = $correctionRequest->attendance;

        // 修正申請を承認済みにする
            $correctionRequest->update([
                'status' => 'approved',
            ]);

        // 勤怠情報更新
            $attendance->update([
                'clock_in' => $correctionRequest->start_time,
                'clock_out' => $correctionRequest->end_time,
                'remark' => $correctionRequest->note,
            ]);

        // 既存休憩削除
            $attendance->breakTimes()->delete();

        // 修正申請の休憩を勤怠へ反映
            foreach ($correctionRequest->breakTimes ?? [] as $breakTime) {
                $attendance->breakTimes()->create([
                    'break_start' => $breakTime->break_start,
                    'break_end' => $breakTime->break_end,
                ]);
            }
        });

        return redirect()->route('admin.request.list')
            ->with('success', '承認しました');
    }
}
