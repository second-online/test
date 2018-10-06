<?php

use Illuminate\Database\Seeder;
use App\Broadcast;
use Carbon\Carbon;

class BroadcastsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    // 		$broadcasts = [ 
    // 			['name' => 'Monday 6:28am', 'day' => 'monday', 'time' => '06:28:00'],
    //             ['name' => 'Monday 8:00am', 'day' => 'monday', 'time' => '08:00:00'],
    // 			['name' => 'Monday 11:00am', 'day' => 'monday', 'time' => '11:00:00'],
    // 			['name' => 'Monday 5:00pm', 'day' => 'monday', 'time' => '17:00:00'],
    // 			['name' => 'Monday 7:00pm', 'day' => 'monday', 'time' => '19:00:00'],
    //             ['name' => 'Monday 9:00pm', 'day' => 'monday', 'time' => '21:00:00'],
    //             ['name' => 'Monday 10:00pm', 'day' => 'monday', 'time' => '22:00:00'],
    // 			['name' => 'Tuesday 8:00am', 'day' => 'tuesday', 'time' => '08:00:00'],
    // 			['name' => 'Tuesday 11:00am', 'day' => 'tuesday', 'time' => '11:00:00'],
    // 			['name' => 'Tuesday 5:00pm', 'day' => 'tuesday', 'time' => '17:00:00'],
    // 			['name' => 'Tuesday 10:30pm', 'day' => 'tuesday', 'time' => '22:30:00'],
    // 			['name' => 'Wednesday 8:00am', 'day' => 'wednesday', 'time' => '08:00:00'],
				// ['name' => 'Wednesday 9:00am', 'day' => 'wednesday', 'time' => '09:00:00'],
				// ['name' => 'Wednesday 10:00am', 'day' => 'wednesday', 'time' => '10:00:00'],
				// ['name' => 'Wednesday 12:00pm', 'day' => 'wednesday', 'time' => '12:00:00'],
    // 			['name' => 'Thursday 4:00am', 'day' => 'thursday', 'time' => '04:00:00'],
				// ['name' => 'Thursday 8:00am', 'day' => 'thursday', 'time' => '08:00:00'],
				// ['name' => 'Thursday 6:00pm', 'day' => 'thursday', 'time' => '18:00:00'],
				// ['name' => 'Thursday 10:00pm', 'day' => 'thursday', 'time' => '22:00:00'],
    // 			['name' => 'Friday 5:00am', 'day' => 'friday', 'time' => '05:00:00'],
				// ['name' => 'Friday 12:00pm', 'day' => 'friday', 'time' => '12:00:00'],
				// ['name' => 'Friday 6:00pm', 'day' => 'friday', 'time' => '18:00:00'],
				// ['name' => 'Friday 11:00pm', 'day' => 'friday', 'time' => '23:00:00'],
				// ['name' => 'Saturday 7:00am', 'day' => 'saturday', 'time' => '07:00:00'],
				// ['name' => 'Saturday 11:00am', 'day' => 'saturday', 'time' => '11:00:00'],
				// ['name' => 'Saturday 4:00pm', 'day' => 'saturday', 'time' => '16:00:00'],
				// ['name' => 'Sunday Worship 9:30am', 'day' => 'sunday', 'time' => '09:30:00', 'live' => true,],
				// ['name' => 'Sunday Worship 11:00am', 'day' => 'sunday', 'time' => '11:00:00', 'live' => true,],
    // 		];

            $broadcasts = [ 
                ['name' => 'Friday 4:03am', 'day' => 'friday', 'time' => '04:05:00'],
                ['name' => 'Friday 6:00am', 'day' => 'friday', 'time' => '06:00:00'],
                ['name' => 'Saturday 2:35am', 'day' => 'saturday', 'time' => '04:50:00'],
//                 ['name' => 'Sunday 9:30am', 'day' => 'sunday', 'time' => '09:30:00', 'live' => 1, 'embed_code' => '<div id="la1-video-player" data-embed-id="f87344c6-2e5a-49fa-8dc2-863a8ca08453"></div>
// <script type="application/javascript" data-main="//control.livingasone.com/webplayer/loader.js" src="//control.livingasone.com/webplayer/require.js"></script>'
//                 ],
            ];

    		foreach($broadcasts as $broadcast) {

                // $format = 'l H:i:s';
                // $time = $broadcast['day'] . ' ' . $broadcast['time'];
                // $timezone = 'America/Chicago';
                // $date = Carbon::createFromFormat($format, $time, $timezone);

                // if ($date->isPast()) {
                //     $date->addWeek();
                // }

                //$broadcast['next_gathering'] = $date->toDateTimeString();
                // $broadcast['next_gathering_utc'] = $date->timezone('utc')->toDateTimeString();

    			Broadcast::create($broadcast);
    		}
    }
}
