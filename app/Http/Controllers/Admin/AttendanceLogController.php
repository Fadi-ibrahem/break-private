<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\AttendanceLog;
use Illuminate\Support\Carbon;
use App\Exports\AttendancesExport;
use App\Http\Controllers\Controller;

class AttendanceLogController extends Controller
{

    public function index()
    {
        $employees = User::whereNotNull('code')->select('name', 'code')->orderBy('code')->get();
        if(auth()->user()->type != 'super_admin' && auth()->user()->type == 'supervisor') {
            $employees = User::whereNotNull('code')->where('supervisor_id', auth()->user()->id)->select('name', 'code')->orderBy('code')->get();
        }
        $logs = AttendanceLog::getLogs();

        return view('admin.attendance_log.index', compact('logs', 'employees'));
    }

    public function export()
    {
        return new AttendancesExport();
    }
}
