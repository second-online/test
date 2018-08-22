<?php

use Illuminate\Database\Seeder;
use App\Series;

class SeriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $series = new Series;
        $series->name = "Made to Climb";
        $series->save();
    }
}
