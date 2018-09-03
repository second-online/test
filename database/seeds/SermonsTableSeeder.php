<?php

use Illuminate\Database\Seeder;
use App\Sermon;
use App\Speaker;
use App\Series;

class SermonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $sermons = factory(App\Sermon::class, 100)->create();

		// $speaker = Speaker::find(1);
		// $series = Series::find(1);

  //       $sermon = new Sermon;
  //       $sermon->title = "You of Little Faith";
  //       $sermon->description = "Short description of this video";
  //       $sermon->notes = "Notes for this video";
  //       $sermon->youtube_id = "Lesh9EkwgV8";
  //       $sermon->speaker()->associate($speaker);
  //       $sermon->series()->associate($series);
  //       $sermon->save();
    }
}
