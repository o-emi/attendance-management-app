<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceReportController;

class AttendanceReportController extends Controller
{
    public function index()
    {
        return view('attendance.report');
    }
        
}
