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

Route::get('/', function () {
	return view('welcome');
});


Route::get('/test', function () {

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
	// $sorted = $schedule->sortBy('time');
	// var_dump($sorted);


	$format = 'l H:i:s'; // l, g:ia

	$time = 'Sunday 11:11:00';
	$timezone = 'America/Chicago';
	$inputDate = Carbon::createFromFormat($format, $time, $timezone);
 
 	var_dump($inputDate);

 	var_dump($inputDate->setTimezone('UTC'));

	return;


	$format = 'Y-m-d H:i:s';

	$time = '2018-2-24 20:00:00';
	$timezone = 'America/Chicago';
	$inputDate = Carbon::createFromFormat($format, $time, $timezone);
	var_dump($inputDate);
 
	$time = (string)$inputDate->timezone('UTC');
	$timezone = 'UTC';
	$dbDate = Carbon::createFromFormat($format, $time, $timezone);

	var_dump($dbDate);

	var_dump($dbDate->timezone('America/Chicago'));


	return;
});