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

	Route::get('home', 'HomeController@index');

	Route::resource('sermons', 'SermonController')->only([
	    'index', 'show'
	]);

	Route::get('schedule', 'BroadcastController@index');

	Route::get('broadcasts/{broadcast}', 'BroadcastController@show');

	Route::get('broadcasts/{broadcast}/comments', 'BroadcastCommentController@index');

	Route::post('broadcasts/{broadcast}/comments', 'BroadcastCommentController@store');

	Route::post('login', 'Auth\LoginController@login');

	Route::post('register', 'Auth\RegisterController@register');

	Route::post('logout', 'Auth\LoginController@logout');

	Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');

	Route::post('password/reset', 'Auth\ResetPasswordController@reset');

	Route::get('user/me', 'UserController@me');

	Route::patch('user/edit', 'UserController@update');

	Route::patch('user/picture', 'UserController@updateProfilePicture');

	Route::delete('user/picture', 'UserController@destroyProfilePicture');

	Route::post('contact', 'ContactController@index');
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


Route::get('/mailable', function () {
    $email = 'alex@alexlacayo.com';
    $name = 'Alex Lacayo';
    $body = 'Hey can you help me?';

    return new App\Mail\ContactUs($email, $name, $body);
});

Route::get('test', function() {

});

Route::get('vimeo/{id}', function($id) {

	$opts = array(
	  'http'=>array(
	    'header'=> 'Authorization: Bearer ' . $id
	  )
	);

	$context = stream_context_create($opts);

	// Open the file using the HTTP headers set above
	$response = json_decode(file_get_contents('https://api.vimeo.com/me/videos?per_page=100&page=1', true, $context));

	$videos = $response->data;

	$date = Carbon::createFromFormat('Y-m-d H:i:s', '2018-10-31 00:00:00');

	foreach ($videos as $key => $video) {
		if ($video->duration < 300)
			continue;

		if ($key === 0)
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
});
