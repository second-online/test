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

// Route::get('{any}', function() {
// 	echo 'Bro, anyyyyyy.....';
// })->where('any', '.*');

Auth::routes();

Route::get('login', function() {

	Auth::loginUsingId(10000, true);

	$user = Auth::user();

	echo $user->id;

});

Route::fallback('SPAController@index');


Route::group(['prefix' => 'w/api'], function() { 

	Route::resource('sermons', 'SermonController')->only([
	    'index', 'show'
	]);

	Route::get('broadcasts/{broadcast}', 'BroadcastController@show');

	Route::get('broadcasts/{broadcast}/comments', 'BroadcastCommentController@index');

	Route::post('broadcasts/{broadcast}/comments', 'BroadcastCommentController@store')
		->name('broadcasts.comments.create');
});


Route::group(['prefix' => 'w/api/host'], function() { 
 

});


// Route::get('/', function() {
// 	return view('layouts.app');
// });

// Route::resource('sermons', 'SermonController');
// Route::resource('broadcasts', 'BroadcastController');







// Route::get('/schedule', function () {

	// event(new App\Events\BroadcastCommentCreated('Hello bro!'));

	// $broadcasts = Broadcast::all();
	// $schedule = collect();

	// foreach ($broadcasts as $broadcast) {

	// 	$format = 'l H:i:s';
	// 	$time = $broadcast->day . ' ' . $broadcast->time;
	// 	$timezone = 'America/Chicago';
	// 	$date = Carbon::createFromFormat($format, $time, $timezone);

	// 	if ($date->isPast()) {
	// 		$date->addWeek();
	// 	}

	// 	$schedule->push(['name' => $broadcast->name, 'time' => (string)$date]);
	// }

	// // var_dump($schedule);
	// $sorted = $schedule->sortBy('time');
	// var_dump($sorted);
	
// });