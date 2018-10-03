<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Broadcast; 
use App\Sermon;
use Carbon\Carbon;
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $broadcast = Broadcast::find($id);

        // use model exception middleware or something?
        if (! $broadcast) {
            return response()->json(['message' => 'Page not found.'], 404);
        }

        if (! $broadcast->live) {
            // Check active date
            $sermon = Sermon::orderBy('id', 'desc')->first();
            $broadcast->sermon = $sermon;
            $broadcast->trailer = ['link' => 'https://vimeo.com/218845426/1d582e7485'];
            $broadcast->getStatus(2700);
            
        }

        $response = [
            'broadcast' => $broadcast,
        ];

        // Add the trailer
        return response()->json([
            'broadcast' => $broadcast,
        ]);
    }
}
