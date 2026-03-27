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
