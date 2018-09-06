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
        $this->middleware(['auth', 'ajax']);
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
        $text = $request->input('text');
        $user = $request->user();

        $broadcastComment = new BroadcastComment;
        $broadcastComment->text = $text;
        $broadcastComment->user()->associate($user);
        $broadcastComment->broadcast_id = $broadcast_id;
        
        // change to try catch /// return something
        if ($broadcastComment->save()) {
            $data = $broadcastComment->toArray();

            broadcast(new BroadcastCommentCreated($data))->toOthers();

            $response = [
                'comment_created' => true,
                'comment' => $data,
            ];

            return response()->json($response);
        } else {
            return response()->json(['message' => 'Something happened. Try again.'], 500);
        }
    }
}
