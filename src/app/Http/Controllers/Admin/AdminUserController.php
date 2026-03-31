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

        $attendances = Attendance::where('user_id', $id)
            ->orderBy('work_date', 'desc')
            ->get();

        $currentMonth = Carbon::now()->format('Y年m月');

        return view('admin.staff.attendance', compact(
            'user',
            'attendances',
            'currentMonth'
        ));
    }
}
