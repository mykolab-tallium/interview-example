<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/test', function () {
//    $users = \App\Models\User::all();
//
//    foreach ($users as $user) {
//        echo $user->first_name;
//        // Perform some action on user
//    }

    \App\Models\User::query()->cursor()->each(function ($user) {
        echo $user->first_name . PHP_EOL;
    });
});
