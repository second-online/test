<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SPAController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {	
    	$user = Auth::user();

    	if (!is_null($user) && $user->isHost()) {
	    	$user->is_host = true; 
    	}

        // Attach next broadcasdt too...

        return view('layouts.app')->with('user', $user);
    }
}
