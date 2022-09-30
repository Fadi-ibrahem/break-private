<?php

namespace App\Http\Controllers\Admin;

use Request;
use App\Models\User;
use App\Imports\UsersImport;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\Admin\UserRequest;
use Illuminate\Http\Request as HttpRequest;
use App\Http\Requests\Admin\SupervisorEmpRequest;
use App\Http\Requests\Admin\ManagerSupervisorRequest;

class UserController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:read_users')->only(['index']);
        $this->middleware('permission:create_users')->only(['create', 'store']);
        $this->middleware('permission:update_users')->only(['edit', 'update']);
        $this->middleware('permission:delete_users')->only(['delete', 'bulk_delete']);
    } // end of __construct

    public function import()
    {
        Excel::import(new UsersImport, request()->file('employees'));

        return redirect('/')->with('success', 'All good!');
    }

    public function index()
    {
        return view('admin.users.index');
    } // end of index

    public function data()
    {
        $users = User::whereNotNull('type')->select();

        if(auth()->user()->type == 'supervisor') {
            $users->where([
                ['type', '=', 'employee'],
                ['supervisor_id', '=', auth()->user()->id],
            ]);
        } elseif(auth()->user()->type == 'manager') {
            $users->where('manager_id', '=', auth()->user()->id)
            ->orWhereIn('supervisor_id', auth()->user()->managerSupervisors->pluck('id'));

            return DataTables::of($users)
                ->addColumn('record_select', 'admin.users.data_table.record_select')
                ->editColumn('created_at', function (User $user) {
                    return ($user->created_at) ? $user->created_at->format('Y-m-d') : "";
                })
                ->addColumn('related_supervisor', function(User $user) {
                    return ($user->supervisor) ? $user->supervisor->name : "~";
                })
                ->addColumn('actions', 'admin.users.data_table.actions')
                ->rawColumns(['record_select', 'actions'])
                ->toJson();
        }

        if(auth()->user()->type == 'super_admin') {
            return DataTables::of($users)
                ->addColumn('record_select', 'admin.users.data_table.record_select')
                ->editColumn('created_at', function (User $user) {
                    return ($user->created_at) ? $user->created_at->format('Y-m-d') : "";
                })
                ->addColumn('related_supervisor', function(User $user) {
                    return ($user->supervisor) ? $user->supervisor->name : "~";
                })
                ->addColumn('related_manager', function(User $user) {
                    return ($user->supervisorManager) ? $user->supervisorManager->name : "~";
                })
                ->addColumn('actions', 'admin.users.data_table.actions')
                ->rawColumns(['record_select', 'actions'])
                ->toJson();
        }

        return DataTables::of($users)
            ->addColumn('record_select', 'admin.users.data_table.record_select')
            ->editColumn('created_at', function (User $user) {
                return ($user->created_at) ? $user->created_at->format('Y-m-d') : "";
            })
            ->addColumn('actions', 'admin.users.data_table.actions')
            ->rawColumns(['record_select', 'actions'])
            ->toJson();
    } // end of data

    public function create()
    {
        $supervisors    = User::where('type', 'supervisor')->get();
        $managers    = User::where('type', 'manager')->get();
        return view('admin.users.create', compact('supervisors', 'managers'));
    } // end of create

    public function store(UserRequest $request)
    {
        $requestData = $request->validated();
        $requestData['password'] = bcrypt($request->password);

        User::create($requestData);

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('admin.users.index');
    } // end of store

    public function edit(User $user)
    {
        $supervisors    = User::where('type', 'supervisor')->get();
        return view('admin.users.edit', compact(['user', 'supervisors']));
    } // end of edit

    public function update(UserRequest $request, User $user)
    {
        $requestData = $request->validated();
        $user->update($requestData);

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('admin.users.index');
    } // end of update

    public function destroy(User $user)
    {
        $this->delete($user);
        session()->flash('success', __('site.deleted_successfully'));
        return response(__('site.deleted_successfully'));
    } // end of destroy

    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $user = User::FindOrFail($recordId);
            $this->delete($user);
        } //end of for each

        session()->flash('success', __('site.deleted_successfully'));
        return response(__('site.deleted_successfully'));
    } // end of bulkDelete

    private function delete(User $user)
    {
        $user->delete();
    } // end of delete


    // Start Assign Employees To A Supervisor
    public function showAssign()
    {
        $employees      = User::where('type', 'employee')->get();
        $supervisors    = User::where('type', 'supervisor')->get();
        return view('admin.users.supervisor_emp', compact(['employees', 'supervisors']));
    }

    public function assign(SupervisorEmpRequest $request)
    {
        $employees  = $request->employees;

        User::whereIn('id', $employees)->update(['supervisor_id'=>$request->supervisor_id]);

        session()->flash('success', __('site.assigned_successfully'));
        return redirect()->route('admin.users.index');
    }
    // End Assign Employees To A Supervisor


    // Start Supervisor Assistant
    public function changeAssistStatus(HttpRequest $request) 
    {
        User::where('id', $request->id)->update(['is_assist' => $request->status]);
        return response()->json(['message'=>'record updated successfully']);
    }
    // End Supervisor Assistant


    // Start Assign Manager To Supervisor
    public function showAssignManager()
    {
        $supervisors    = User::where('type', 'supervisor')->get();
        $managers      = User::where('type', 'manager')->get();
        return view('admin.users.manager_supervisors', compact(['supervisors', 'managers']));
    }

    public function assignManager(ManagerSupervisorRequest $request)
    {
        $supervisors  = $request->supervisors;

        User::whereIn('id', $supervisors)->update(['manager_id'=>$request->manager_id]);

        session()->flash('success', __('site.assigned_successfully'));
        return redirect()->route('admin.users.index');
    }
    // End Assign Manager To Supervisor

}//end of controller
