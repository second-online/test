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
use App\User;
use App\Role;

// Route::get('{any}', function() {
// 	echo 'test.....';
// })->where('any', '.*');

Auth::routes();

Route::get('login', function() {

	Auth::loginUsingId(10000, true);
});

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

	$user = User::find(10001);
	$user->roles()->attach($role);
});




Route::group(['prefix' => 'w/api'], function() { 

	Route::resource('sermons', 'SermonController')->only([
	    'index', 'show'
	]);

	Route::get('broadcasts/{broadcast}', 'BroadcastController@show');

	Route::get('broadcasts/{broadcast}/comments', 'BroadcastCommentController@index');

	Route::post('broadcasts/{broadcast}/comments', 'BroadcastCommentController@store')
		->name('broadcasts.comments.create');

});


Route::group(['prefix' => 'w/api/host', 'namespace' => 'Host'], function() { 
 	
 	Route::get('authorize', 'AuthorizeController@check');

	Route::get('dashboard', 'DashboardController@index');

	Route::post('comments', 'CommentController@store');

});




// Route::get('/schedule', function () {

// 	$broadcasts = Broadcast::all();

// 	foreach ($broadcasts as $broadcast) {
// 		$broadcast->save();
// 	}
// 	die;

// 	$broadcasts = Broadcast::all();
// 	$schedule = collect();

// 	foreach ($broadcasts as $broadcast) {

// 		$format = 'l H:i:s';
// 		$time = $broadcast->day . ' ' . $broadcast->time;
// 		$timezone = 'America/Chicago';
// 		$date = Carbon::createFromFormat($format, $time, $timezone);

// 		if ($date->isPast()) {
// 			$date->addWeek();
// 		}
		
// 		$newDate = clone $date;
// 		echo $newDate->addHour()->toDateTimeString();
// 		echo "<br>";
// 		echo $date->toDateTimeString();
// die;
// 		// $date->timezone('utc');

// 		$schedule->push([
// 			'name' => $broadcast->name,
// 			'time' => $date->toDateTimeString(),
// 			'time utc' => $date->timezone('UTC')->toDateTimeString()
// 		]);
// 	}

// 	// var_dump($schedule);
// 	$sorted = $schedule->sortBy('time');
// 	var_dump($sorted);
	

// return;

// 	$broadcasts = Broadcast::all();

// 	foreach ($broadcasts as $broadcast) {

// 		echo $broadcast->next_gathering;
// 		return;

// 	}

// 	return response()->json($broadcasts);
// });



 
Route::fallback('SPAController@index');