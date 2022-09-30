<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\AttendanceLog;
use App\Exports\AttendancesExport;
use App\Http\Controllers\Controller;

class AttendanceLogController extends Controller
{

    public function index()
    {
        $employees = User::whereNotNull('code')->select('name', 'code')->orderBy('code');

        if(auth()->user()->type != 'super_admin' && auth()->user()->type == 'supervisor') {
            $employees->where('supervisor_id', auth()->user()->id);
        } elseif(auth()->user()->type != 'super_admin' && auth()->user()->type == 'manager') {
            $employees->where('manager_id', auth()->user()->id);
        }

        $employees = $employees->get();
        $logs = AttendanceLog::getLogs();

        return view('admin.attendance_log.index', compact('logs', 'employees'));
    }

    public function export()
    {
        return new AttendancesExport();
    }
}
