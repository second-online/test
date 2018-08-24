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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');





Route::get('/schedule', function () {

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

		$schedule->push(['name' => $broadcast->name, 'time' => (string)$date]);
	}

	// var_dump($schedule);
	$sorted = $schedule->sortBy('time');
	var_dump($sorted);
	
});