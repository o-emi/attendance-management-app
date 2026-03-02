<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());

        $users = User::with(['attendances' => function ($query) use ($date) {
        $query->whereDate('work_date', $date);
        }])->get();

        return view('admin.attendance.list', compact('users', 'date'));
    }

    public function show($id)
    {
        $attendance = Attendance::with('user', 'breakTimes')->findOrFail($id);

        return view('admin.attendance.show', compact('attendance'));
    }
}
