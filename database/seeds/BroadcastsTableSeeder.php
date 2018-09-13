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
    // 			['name' => 'Monday 8:00am', 'day' => 'monday', 'time' => '8:00:00'],
    // 			['name' => 'Monday 11:00am', 'day' => 'monday', 'time' => '11:00:00'],
    // 			['name' => 'Monday 5:00pm', 'day' => 'monday', 'time' => '17:00:00'],
    // 			['name' => 'Monday 9:00pm', 'day' => 'monday', 'time' => '21:00:00'],
    // 			['name' => 'Tuesday 8:00am', 'day' => 'tuesday', 'time' => '8:00:00'],
    // 			['name' => 'Tuesday 11:00am', 'day' => 'tuesday', 'time' => '11:00:00'],
    // 			['name' => 'Tuesday 5:00pm', 'day' => 'tuesday', 'time' => '17:00:00'],
    // 			['name' => 'Tuesday 9:00pm', 'day' => 'tuesday', 'time' => '21:00:00'],
    // 			['name' => 'Wednesday 8:00am', 'day' => 'wednesday', 'time' => '8:00:00'],
				// ['name' => 'Wednesday 9:00am', 'day' => 'wednesday', 'time' => '9:00:00'],
				// ['name' => 'Wednesday 10:00am', 'day' => 'wednesday', 'time' => '10:00:00'],
				// ['name' => 'Wednesday 12:00pm', 'day' => 'wednesday', 'time' => '12:00:00'],
    // 			['name' => 'Thursday 4:00am', 'day' => 'thursday', 'time' => '4:00:00'],
				// ['name' => 'Thursday 8:00am', 'day' => 'thursday', 'time' => '8:00:00'],
				// ['name' => 'Thursday 6:00pm', 'day' => 'thursday', 'time' => '18:00:00'],
				// ['name' => 'Thursday 10:00pm', 'day' => 'thursday', 'time' => '22:00:00'],
    // 			['name' => 'Friday 5:00am', 'day' => 'friday', 'time' => '5:00:00'],
				// ['name' => 'Friday 12:00pm', 'day' => 'friday', 'time' => '12:00:00'],
				// ['name' => 'Friday 6:00pm', 'day' => 'friday', 'time' => '18:00:00'],
				// ['name' => 'Friday 11:00pm', 'day' => 'friday', 'time' => '23:00:00'],
				// ['name' => 'Saturday 7:00am', 'day' => 'saturday', 'time' => '7:00:00'],
				// ['name' => 'Saturday 11:00am', 'day' => 'saturday', 'time' => '11:00:00'],
				// ['name' => 'Saturday 4:00pm', 'day' => 'saturday', 'time' => '16:00:00'],
				// ['name' => 'Sunday Worship 9:30am', 'day' => 'sunday', 'time' => '9:30:00', 'live' => true,],
				// ['name' => 'Sunday Worship 11:00am', 'day' => 'sunday', 'time' => '11:00:00', 'live' => true,],
    // 		];

            $broadcasts = [ 
                ['name' => 'Tuesday 11:00pm', 'day' => 'tuesday', 'time' => '23:00:00'],
                ['name' => 'Wednesday 4:40am', 'day' => 'wednesday', 'time' => '04:40:00'],
                ['name' => 'Wednesday 5:00am', 'day' => 'wednesday', 'time' => '05:00:00'],
                ['name' => 'Wednesday 8:00pm', 'day' => 'wednesday', 'time' => '20:00:00'],
                ['name' => 'Thursday 6:57am', 'day' => 'thursday', 'time' => '06:57:00'],
                ['name' => 'Thursday 5:26am', 'day' => 'thursday', 'time' => '05:26:00'],

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
