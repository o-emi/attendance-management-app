<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'user')->get();

        return view('admin.staff.index', compact('users'));
    }

    public function showAttendance($id)
    {
        $user = User::findOrFail($id);

        $currentMonth = request('month', Carbon::now()->format('Y-m'));

        $attendances = Attendance::where('user_id', $id)
        ->whereYear('work_date', Carbon::parse($currentMonth)->year)
        ->whereMonth('work_date', Carbon::parse($currentMonth)->month)
        ->orderBy('work_date', 'desc')
        ->get();

        return view('admin.staff.attendance', compact(
            'user',
            'attendances',
            'currentMonth'
        ));
    }
}
