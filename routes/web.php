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

use Carbon\Carbon;
use App\Broadcast;
use App\Sermon;
use App\User;
use App\Role;

// Route::get('{any}', function() {
// 	echo 'test.....';
// })->where('any', '.*');

//Auth::routes();

// Route::get('login', function() {
// echo 'keke';
// 	Auth::loginUsingId(10000, true);
// });


Route::get('jeff', function() {

	Auth::loginUsingId(10001, true);
});

Route::get('logout', function() {

	Auth::logout();
});

Route::get('role', function() {
	
	$role = Role::find(2);
	$user = User::find(10000);
	$user->roles()->attach($role);

	// $user = User::find(10001);
	// $user->roles()->attach($role);
});


Route::group(['prefix' => 'w/api'], function() { 

	Route::resource('sermons', 'SermonController')->only([
	    'index', 'show'
	]);

	Route::get('broadcasts/{broadcast}', 'BroadcastController@show');

	Route::get('broadcasts/{broadcast}/comments', 'BroadcastCommentController@index');

	Route::post('broadcasts/{broadcast}/comments', 'BroadcastCommentController@store');

	Route::post('login', 'Auth\LoginController@login');

	Route::post('register', 'Auth\RegisterController@register');

	Route::post('logout', 'Auth\LoginController@logout');

	Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');

	Route::post('password/reset', 'Auth\ResetPasswordController@reset');

});


Route::group(['prefix' => 'w/api/host', 'namespace' => 'Host'], function() { 
 	
 	// Route::get('authorize', 'AuthorizeController@check');

	Route::get('dashboard', 'DashboardController@index');

	Route::post('comments', 'CommentController@store');

});


Route::fallback('SPAController@index');

// Define this route so we dont get 'password.reset' route 404 error.
Route::get('password/reset/{token}', 'SPAController@index')->name('password.reset');


Route::get('test', function() {

        $now = Carbon::now();

        $broadcasts = Broadcast::where('enabled', 1)
            ->orderBy('starts_at')
            ->get();



    	return response()->json([ 
            'now' => $now->toDateTimeString(),
            'schedule' => $broadcasts,
        ]);
});


Route::get('/schedule', function () {

	$format = 'Y-m-d H:i';
	$now = new Carbon();
	$sermon = Sermon::orderBy('id', 'desc')->first();

	$broadcasts = Broadcast::where('enabled', 1)->get();

	foreach ($broadcasts as $broadcast) {
		// if time is opens_at == starts_at - 10 min
		// if time is = starts_at
		// if closes_at is in the past

		$startsAt = Carbon::createFromFormat('Y-m-d H:i:s', $broadcast->starts_at);
		$opensAt = clone $startsAt;
		$opensAt->subMinutes(10);
		$closesAt = clone $startsAt;
        $closesAt->addSeconds($sermon->duration);
        $closesAt->addMinutes(10);
        $closesAt->second = 0;
		
		echo $opensAt . '<br>' . $startsAt . '<br>' . $closesAt;

		die;

		if ($opensAt->format($format) == $now->format($format)) {
			// fire open event
			//echo "open at right now" . '<br>';
		} else if ($startsAt->format($format) == $now->format($format)) {
			// fire broadcast starting event
			//echo "broadcast starts now" . '<br>';
		} else if ($closesAt->isPast()) {
			// save broadcast so our listener can update the new timestamp
			$broadcast->save();

			// fire broadcast updated event?
		}
	}

die;

	$broadcasts = Broadcast::all();
	$schedule = collect();

	foreach ($broadcasts as $broadcast) {

		$format = 'l H:i:s';
		$time = $broadcast->day . ' ' . $broadcast->time;
		$timezone = 'America/Chicago';
		$date = Carbon::createFromFormat($format, $time, $timezone);

		if ($date->isPast()) {
			$date->addWeek();
		}
		
		$newDate = clone $date;
		echo $newDate->addHour()->toDateTimeString();
		echo "<br>";
		echo $date->toDateTimeString();
die;
		// $date->timezone('utc');

		$schedule->push([
			'name' => $broadcast->name,
			'time' => $date->toDateTimeString(),
			'time utc' => $date->timezone('UTC')->toDateTimeString()
		]);
	}

	// var_dump($schedule);
	$sorted = $schedule->sortBy('time');
	var_dump($sorted);
	

return;

});



 
