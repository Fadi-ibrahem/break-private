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
        return $user->type == 'supervisor' ||  $user->type == "super_admin" || $user->is_assist;
    }

    public function viewBreaks(User $user)
    {
        return $user->type == "employee";
    }
    public function viewReports(User $user)
    {
        return $user->type == "super_admin" || $user->type == "supervisor";
    }

    public function create(User $user)
    {
        return $user->type == "employee" &&
            $user->breaks()->pending()->count() == 0
            && $user->breaks()->active()->count() == 0;
    }

    public function update(User $user, BreakModel $break)
    {
        return ($break->employee->supervisor_id == $user->id || $user->is_assist) && is_null($break->is_approved);
    }
}
