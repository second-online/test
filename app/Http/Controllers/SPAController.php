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
	    	$user = array_add($user->toArray(), 'is_host', true); 
    	}

        return view('layouts.app')->with('user', $user);
    }
}
