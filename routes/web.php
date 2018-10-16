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
use App\HostComment;

// Route::get('{any}', function() {
// 	echo 'test.....';
// })->where('any', '.*');

//Auth::routes();

// Route::get('login', function() {
// echo 'keke';
// 	Auth::loginUsingId(10000, true);
// });


// Route::get('jeff', function() {

// 	Auth::loginUsingId(10001, true);
// });

// Route::get('logout', function() {

// 	Auth::logout();
// });

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

	Route::get('schedule', 'BroadcastController@index');

	Route::get('broadcasts/next', 'BroadcastController@upNext');

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
 	
	Route::get('dashboard', 'HostDashboardController@index');

	Route::get('comments', 'HostCommentController@index');

	Route::post('comments', 'HostCommentController@store');

});

Route::group(['prefix' => 'w/api/admin'], function() { 

	Route::put('sermons/{sermon}/edit', 'SermonController@update');

});

Route::fallback('SPAController@index');

// Define this route so we dont get 'password.reset' route 404 error.
Route::get('password/reset/{token}', 'SPAController@index')->name('password.reset');


Route::get('test', function() {

	$date = Carbon::createFromFormat('Y-m-d H:i:s', '2018-10-10 00:00:00');
	$date->tz('America/Chicago');

	var_dump($date);

	$date->subWeek();

	var_dump($date);

	die;


    $broadcast = Broadcast::where('enabled', 1)
        ->oldest('starts_at')
        ->first();

    $broadcast->configure();


    if (! isset($broadcast->sermon)) {
    	$sermon = $broadcast->loadSermon();

    	$notes = $sermon->notes;

    	echo $notes;
    }

	// $sermon = Sermon::find(1);

	// $sermon->description = '<h1>haha what up</h1><p>story of my life..</p><script>alert("hacked")</script>';

	// $sermon->save();

	// echo $sermon->description;
});

Route::get('vimeo', function() {


	// $sermon = Sermon::find(1);

	// echo $sermon->duration;

	// die;

	$opts = array(
	  'http'=>array(
	    'header'=> 'Authorization: Bearer 1145ef4001404718357f5bf704dcc536'
	  )
	);

	$context = stream_context_create($opts);

	// Open the file using the HTTP headers set above
	$response = json_decode(file_get_contents('https://api.vimeo.com/me/videos?per_page=10&page=1', true, $context));

	$videos = $response->data;

	$date = Carbon::createFromFormat('Y-m-d H:i:s', '2018-10-10 00:00:00');

	foreach ($videos as $video) {
		if ($video->duration < 300)
			continue;


		// echo $video->name . '<br>';
		// echo str_replace('/videos/', '', $video->uri) . '<br>';
		// echo $video->duration . '<br>';

		// foreach($video->pictures->sizes as $picture) {
		// 	//var_dump(gettype($picture->width));
			
		// 	if ($picture->width == 1920) {
		// 		$img = str_replace('?r=pad', '', $picture->link);
		// 		echo '<a target="_blank" href="'.$img.'">' . $img . '</a>' . '<br>';
		// 	}
		// }

		// echo '<br>';

		$sermon = new Sermon;
		$sermon->title = $video->name;
		$sermon->vimeo_id = str_replace('/videos/', '', $video->uri);
		$sermon->duration = $video->duration;
		$sermon->speaker_id = 1;
		$sermon->description = 'In the Sermon on the Mount, Jesus puts down a grid for living the Christian life. In the Beatitudes which introduce the sermon, we see a sketch of the Kingdom individual and his attitude toward himself, God, and others. In this message Dr. Young continues a study of the Beatitudes and gives us a clear picture of the character of the Kingdom man or woman and the blessings that derive from Kingdom living.';
		$sermon->notes = $video->description;
		$sermon->publish_on = $date;

		foreach($video->pictures->sizes as $picture) {
			if ($picture->width == 1920) {
				$img = str_replace('?r=pad', '', $picture->link);
				$sermon->image = $img;
			}
		}
		
		$sermon->save();

		$date->subWeek();
	}

	//return response()->json($videos);

//1145ef4001404718357f5bf704dcc536
});


Route::get('/scheduled', function () {

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



 
