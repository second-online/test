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

        $status = $broadcast->getStatus();

        if ($status == Broadcast::BROADCAST_OPEN) {
            $broadcast->loadTrailer();
        } else if ($status == Broadcast::BROADCAST_IN_PROGRESS) {
            $broadcast->time_elapsed = $broadcast->starts_at->diffInSeconds();
        }

        return response()->json([
            'broadcast' => $broadcast,
            'status' => $status
        ]);
    }
}
