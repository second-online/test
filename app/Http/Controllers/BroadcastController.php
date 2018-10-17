<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Broadcast;
use Auth;

class BroadcastController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // show broadcast schedule
        //latest('starts_at')
        $broadcasts = Broadcast::where('enabled', 1)
            ->orderBy('starts_at')
            ->get();

        return response()->json($broadcasts);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Broadcast  $broadcast
     * @return \Illuminate\Http\Response
     */
    public function show(Broadcast $broadcast)
    {
        $broadcast->configure();

        return response()->json($broadcast);
    }

    /**
     * Get the next broadcast.
     *
     * @return \Illuminate\Http\Response
     */
    public function upNext()
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

        return response()->json($broadcast);
    }
}
