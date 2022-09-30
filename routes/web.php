<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;


Route::prefix(LaravelLocalization::setLocale())->group(function () {
    Auth::routes();
});





Route::group(
    ['middleware' => 'auth', 'prefix' => LaravelLocalization::setLocale(), 'namespace' => 'Site'],
    function () {

        //profile routes
        Route::group(["namespace" => "Profile", "as" => 'profile.'], function () {
            Route::get('profile/edit', 'ProfileController@edit')->name('edit');
            Route::put('profile/update', 'ProfileController@update')->name('update');
            //password routes
            Route::get('profile/password/edit', 'PasswordController@edit')->name('password.edit');
            Route::put('profile/password/update', 'PasswordController@update')->name('password.update');
        });

        Route::get('/', 'HomeController@index')->name('home');
        Route::get('breaks', 'BreakController@index')->name('breaks.index');
        Route::post('breaks', 'BreakController@store')->name('breaks.store');
        Route::put('breaks', 'BreakController@update')->name('breaks.update');


    }
);
