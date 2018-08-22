<?php

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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/test', function () {

		$role = App\Role::find(1);
		$user = App\User::find(1);
		$user->delete();

		//$role->users()->attach($user);
		// $user->roles()->attach($role);

		// foreach ($user->roles as $role) {
		// 	echo $role->name;
		// }

		return;
});