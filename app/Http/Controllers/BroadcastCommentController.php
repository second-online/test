<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Broadcast;
use App\BroadcastComment;
use App\Events\BroadcastCommentCreated;

class BroadcastCommentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created broadcast comment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Broadcast  $broadcast
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Broadcast $broadcast)
    {
        $broadcastComment = new BroadcastComment;
        $broadcastComment->comment = $request->input('comment');
        $broadcastComment->user()->associate($request->user());
        $broadcastComment->broadcast()->associate($broadcast);
        
        if ($broadcastComment->save()) {
            event(new BroadcastCommentCreated($broadcastComment));
        } 
    }
}
