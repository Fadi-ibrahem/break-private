<?php

use App\Models\User;
use App\Models\AttendanceLog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;

Route::group(
    [
        'middleware' => ['auth', 'IsResponsible'],
        'as' => 'admin.',
        'prefix' => LaravelLocalization::setLocale() . '/admin',
    ],
    function () {
        //home
        Route::get('/', 'HomeController@index')->name('home');

        //role routes
        // Route::get('roles/data', 'RoleController@data')->name('roles.data');
        // Route::delete('roles/bulk_delete', 'RoleController@bulkDelete')->name('roles.bulk_delete');
        // Route::resource('roles', 'RoleController');

        //admin routes
        // Route::get('admins/data', 'AdminController@data')->name('admins.data');
        // Route::delete('admins/bulk_delete', 'AdminController@bulkDelete')->name('admins.bulk_delete');
        // Route::resource('admins', 'AdminController');

        //user routes
        Route::post('users/import', 'UserController@import')->name('users.import');
        Route::get('users/data', 'UserController@data')->name('users.data');
        Route::delete('users/bulk_delete', 'UserController@bulkDelete')->name('users.bulk_delete');
        Route::get('users/assign', 'UserController@showAssign')->name('users.showAssign')->middleware('isSuperAdmin');
        Route::post('users/assign', 'UserController@assign')->name('users.assign')->middleware('isSuperAdmin');
        Route::get('users/assign/manager', 'UserController@showAssignManager')->name('users.showAssignManager')->middleware('isSuperAdmin');
        Route::post('users/assign/manager', 'UserController@assignManager')->name('users.assign.manager')->middleware('isSuperAdmin');
        Route::post('users/assist', 'UserController@changeAssistStatus')->name('users.assist');

        Route::resource('users', 'UserController');

        //setting routes
        // Route::get('settings/general', 'SettingController@general')->name('settings.general');
        // Route::get('/settings/social_links', 'SettingController@socialLinks')->name('settings.social_links');
        // Route::get('/settings/mobile_links', 'SettingController@mobileLinks')->name('settings.mobile_links');
        // Route::resource('settings', 'SettingController')->only(['store']);

        // attendance logs
        Route::get('attendance-logs', 'AttendanceLogController@index')->name('attendances.index');
        Route::get('attendance-logs/export/', 'AttendanceLogController@export')->name('attendances.export');

        // breaks
        Route::get('breaks/reports', 'BreakController@reports')->name('breaks.reports');
        Route::get('breaks/reports/export/', 'BreakController@export')->name('breaks.reports.export');
        Route::get('breaks', 'BreakController@index')->name('breaks.index');
        Route::post('breaks/{break}', 'BreakController@update')->name('breaks.update');

        

        Route::get('/test', function() {
            $manager = User::find(163);

            $users = User::whereIn('supervisor_id', $manager->managerSupervisors->pluck('id'))->get();


            foreach($users as $user) {
                dump($user->name);
            }
        });

    }
);
