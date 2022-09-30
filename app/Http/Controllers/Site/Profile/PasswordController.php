<?php

namespace App\Http\Controllers\Site\Profile;

use Illuminate\Http\Request;
use App\Rules\CheckOldPassword;
use App\Http\Controllers\Controller;

class PasswordController extends Controller
{
    public function edit()
    {
        return view('site.profile.password.edit');
    } // end of getChangePassword

    public function update(Request $request)
    {
        $request->validate([
            'old_password' => ['required', new CheckOldPassword],
            'password' => 'required|confirmed'
        ]);

        $request->merge(['password' => bcrypt($request->password)]);

        auth()->user()->update($request->all());

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('profile.password.edit');
    } // end of postChangePassword

}//end of controller
