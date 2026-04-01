<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

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

        $currentMonth = request('month', Carbon::now()->format('Y-m'));
        $month = Carbon::createFromFormat('Y-m', $currentMonth);

        $startOfMonth = $month->copy()->startOfMonth();
        $endOfMonth = $month->copy()->endOfMonth();

        $attendances = Attendance::where('user_id', $id)
            ->whereBetween('work_date', [$startOfMonth, $endOfMonth])
            ->get()
            ->keyBy(function ($attendance) {
                return Carbon::parse($attendance->work_date)->format('Y-m-d');
            });

        $period = CarbonPeriod::create($startOfMonth, $endOfMonth);;

        return view('admin.staff.attendance', compact(
            'user',
            'attendances',
            'currentMonth',
            'period'
        ));
    }
}
