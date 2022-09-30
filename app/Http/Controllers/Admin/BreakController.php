<?php

namespace App\Http\Controllers\Admin;

use App\Events\BreakRequestCurrent;
use Carbon\Carbon;
use App\Models\User;
use App\Models\BreakModel;
use App\Exports\BreaksExport;
use App\Services\Fop2Service;
use App\Http\Controllers\Controller;

class BreakController extends Controller
{

    public function index()
    {
        $this->authorize('viewRequests', BreakModel::class);

        $pendingRequests = [];
        $activeRequests = [];

        // If the current user type is supervisor
        $supervisorId = auth()->user()->id;

        // If the current user type is employee and assigned as supervisor assistant
        if(auth()->user()->type == 'employee' && auth()->user()->is_assist) $supervisorId = auth()->user()->supervisor_id;

        // Get all today break requests which associated with a specific supervisor or his assistants
        $requests = BreakModel::with('employee')->whereHas('employee', function ($query) use($supervisorId) {
            $query->where('supervisor_id', $supervisorId);
        })->today()->orderBy('id', 'desc');

        if(auth()->user()->is_assist){
            $requests->where('employee_id', '!=', auth()->id());
        }

        // Specify for each request which it is a pending or active request
        $requests->get()->each(function ($request) use (&$pendingRequests, &$activeRequests) {
            if (is_null($request->is_approved)) {
                $pendingRequests[] = $request;
            } elseif ($request->is_approved == 1 && is_null($request->end_time)) {
                return $activeRequests[] = $request;
            }
        });

        return view('admin.breaks.index', compact('activeRequests', 'pendingRequests'));
    }

    public function update(BreakModel $break)
    {
        $this->authorize('update', $break);

        if (request()->approve) {
            $break->update([
                'is_approved' =>  1,
                'start_time' => Carbon::now()->toTimeString()
            ]);

            // change fop2 employee status
            if ($ext = $break->employee->extension) {
                Fop2Service::changeStatus($ext, "break");
            }

        } else {
            $break->update([
                'is_approved' =>  0
            ]);
        }

        return response()->json([
            "id" => $break->id,
        ]);
    }

    public function reports()
    {
        $this->authorize('viewReports', BreakModel::class);

        
        if(auth()->user()->type != 'super_admin' && auth()->user()->type == 'supervisor') {
            $employees = User::whereNotNull('code')->where('supervisor_id', auth()->user()->id)->select('name', 'code')->orderBy('code')->get();
        } else {
            $employees = User::whereNotNull('code')->select('name', 'code')->orderBy('code')->get();
        }

        $entries = BreakModel::getReports();

        return view('admin.breaks.reports', compact('employees', 'entries'));
    }


    public function export()
    {
        $this->authorize('viewReports', BreakModel::class);

        return new BreaksExport();
    }
}
