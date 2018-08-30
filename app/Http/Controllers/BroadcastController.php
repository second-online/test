<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Broadcast; 

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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Broadcast  $broadcast
     * @return \Illuminate\Http\Response
     */
    public function show(Broadcast $broadcast)
    {
        $broadcast->load('comments.user');
 
        return response()->json([
            'broadcast' => $broadcast,
        ]);
    }
}
