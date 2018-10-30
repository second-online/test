<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SPAController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
    	$user = Auth::user();

        if (Auth::check()) {
            if ($user->isHost()) {
                $user->is_host = true; 
            }

            $user->makeVisible('email');
            $user->profilePictureSize = 'large';
        }

        // Attach next broadcasdt too...?
        return view('layouts.app')->with('user', $user);
    }
}
