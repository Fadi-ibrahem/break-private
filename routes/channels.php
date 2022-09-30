<?php

use Illuminate\Support\Facades\Broadcast;


// users channel routes
Broadcast::channel('users.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
    return true;
});


// users channel routes
Broadcast::channel('supervisors.{id}', function ($user, $id) {
    if(auth()->user()->type == 'supervisor') {
        return $user->id === (int) $id && Gate::allows('view-break-requests',);
    }
    return $user->supervisor_id === (int) $id && Gate::allows('view-break-requests',);
});
