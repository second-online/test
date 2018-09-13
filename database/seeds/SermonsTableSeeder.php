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

        //$sermons = factory(App\Sermon::class, 100)->create();

   		$speaker = Speaker::find(1);
		$series = Series::find(1);

        $sermon = new Sermon;
        $sermon->title = 'You of Little Faith';
        $sermon->description = 'Short description of this video';
        $sermon->notes = 'Notes for this video';
        $sermon->youtube_id = 'x6HmpEG7OKM';
        $sermon->speaker()->associate($speaker);
        $sermon->series()->associate($series);
        $sermon->duration = '00:45:12';
        $sermon->save();

        $sermon2 = new Sermon;
        $sermon2->title = 'The Secret of Success';
        $sermon2->description = 'Short description of this video';
        $sermon2->notes = 'Notes for this video';
        $sermon2->youtube_id = '6wzty6_LB-U';
        $sermon2->speaker()->associate($speaker);
        $sermon2->series()->associate($series);
        $sermon2->duration = '00:48:12';
        $sermon2->save();

        $sermon3 = new Sermon;
        $sermon3->title = 'The Source of Wisdom';
        $sermon3->description = 'Short description of this video';
        $sermon3->notes = 'Notes for this video';
        $sermon3->youtube_id = 'MP645GgozHE';
        $sermon3->speaker()->associate($speaker);
        $sermon3->series()->associate($series);
        $sermon3->duration = '00:01:32';
        $sermon3->save();
    }
}
