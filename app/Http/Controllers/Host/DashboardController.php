<?php

namespace App\Http\Controllers\Host;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Broadcast;
use App\HostComment;

class DashboardController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    	$this->middleware('auth');
        $this->middleware('role:host');
    }

    public function index(Request $reqest)
    {
        $now = Carbon::now();

        $broadcasts = Broadcast::where('enabled', 1)
            ->orderBy('starts_at')
            ->get();

        $hostComments = HostComment::with('user')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get()
            ->reverse()
            ->values();

    	return response()->json([ 
            'now' => $now->toDateTimeString(),
            'broadcasts' => $broadcasts,
            'host_comments' => $hostComments
        ]);
    }
}
