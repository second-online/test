<?php

use Illuminate\Database\Seeder;
use App\Speaker;

class SpeakersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    		$speaker = new Speaker;
    		$speaker->name = "Pontius Pilate";
    		$speaker->save();
    }
}
