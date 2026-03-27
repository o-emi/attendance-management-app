<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CorrectionRequest;
use Illuminate\Support\Facades\DB;
class CorrectionRequestController extends Controller
{
    public function index()
    {
        $requests = CorrectionRequest::with('user')
            ->latest()
            ->get();

        return view('admin.correction_requests.index', compact('requests'));
    }

    public function show($id)
    {
        $correctionRequest = CorrectionRequest::with('attendance.breakTimes', 'user')
            ->findOrFail($id);

        return view('admin.correction_requests.show', compact('correctionRequest'));
    }

    public function approve($id)
    {
        $correctionRequest = CorrectionRequest::with('attendance')
            ->findOrFail($id);

        DB::transaction(function () use ($correctionRequest) {

            $attendance = $correctionRequest->attendance;

            $correctionRequest->status = 'approved';
            $correctionRequest->save();

            $attendance->clock_in = $correctionRequest->clock_in;
            $attendance->clock_out = $correctionRequest->clock_out;
            $attendance->save();

            $attendance->breakTimes()->delete();

            foreach ($correctionRequest->break_times as $break) {
                $attendance->breakTimes()->create([
                    'start' => $break['start'],
                    'end'   => $break['end'],
                ]);
            }
        });

        return redirect()->route('admin.request.list')
            ->with('success', '承認しました');
    }
}
