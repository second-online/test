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
     * @param  int  $broadcast_id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $broadcast_id)
    {      
        // Need to add validation for text

        $broadcastComment = new BroadcastComment;
        $broadcastComment->text = $request->input('text');
        $broadcastComment->user()->associate($request->user());
        $broadcastComment->broadcast_id = $broadcast_id;

        // change to try catch /// return something
        if ($broadcastComment->save()) {
            
            if ($request->input('isHost') === true) {
                $broadcastComment->user->is_host = $request->user()->isHost();
            } else {
                $broadcastComment->user->is_host = false;
            }

            broadcast(new BroadcastCommentCreated($broadcastComment->toArray()))->toOthers();
            
            $broadcastComment->local_id = $request->input('commentId');

            return response()->json($broadcastComment);
        } else {
            return response()->json(['message' => 'Something happened. Try again.'], 500);
        }
    }
}
