<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Broadcast;
use App\Sermon;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Display resources for home page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {        
        // $broadcast = Broadcast::where('enabled', 1)
        //     ->where('live', 0)
        //     ->oldest('starts_at')
        //     ->first();

        // // remove notes
            
        // $broadcast->configure();

        // if (! isset($broadcast->sermon)) {
        //     $broadcast->loadSermon();
        // }
        $timeThreshold = Carbon::now()->subDays(7);

        $sermons = Sermon::where('publish_on', '<=', $timeThreshold)
        	->take(6)
        	->get();

        return response()->json([
        	// 'broadcast' => $broadcast,
        	'sermons' => $sermons
        ]);
    }
}
