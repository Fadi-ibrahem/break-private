<?php

namespace App\Policies;

use App\Models\User;
use App\Models\BreakModel;
use Illuminate\Auth\Access\HandlesAuthorization;

class BreakModelPolicy
{
    use HandlesAuthorization;

    public function viewRequests(User $user)
    {
        return $user->type == 'supervisor' ||  $user->type == "super_admin" || $user->is_assist || $user->type == 'manager';
    }

    public function viewBreaks(User $user)
    {
        return $user->type == "employee" || $user->type == "supervisor";
    }

    public function viewReports(User $user)
    {
        return $user->type == "super_admin" || $user->type == "supervisor" || $user->type == 'manager';
    }

    public function create(User $user)
    {
        return 
            ($user->type == "employee" || $user->type == "supervisor")
            && $user->breaks()->pending()->count() == 0
            && $user->breaks()->active()->count() == 0;
    }

    public function update(User $user, BreakModel $break)
    {
        return ($break->employee->supervisor_id == $user->id || $break->employee->manager_id == $user->id || $user->is_assist) && is_null($break->is_approved);
    }
}
