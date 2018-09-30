<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Broadcast; 
use App\Sermon;
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

        if (! $broadcast) {
            return response()->json(['message' => 'Page not found.'], 404);
        }

        $sermon = Sermon::orderBy('id', 'desc')->first()->toArray();
        $broadcast = $broadcast->toArray();
        $broadcast = array_add($broadcast, 'sermon', $sermon);

        return response()->json($broadcast);
    }
}
