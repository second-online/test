<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Broadcast;
use App\Sermon;

class HomeController extends Controller
{
    /**
     * Display resources for home page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        $broadcast = Broadcast::where('enabled', 1)
            ->where('live', 0)
            ->oldest('starts_at')
            ->first();

        // remove notes
            
        $broadcast->configure();

        if (! isset($broadcast->sermon)) {
            $broadcast->loadSermon();
        }

        if (! isset($broadcast->trailer)) {
            $broadcast->loadTrailer();
        }

        $sermons = Sermon::where('publish_on', '<', $broadcast->sermon->publish_on)
        	->take(6)
        	->get();

        return response()->json([
        	'broadcast' => $broadcast,
        	'sermons' => $sermons
        ]);
    }
}
