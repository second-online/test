<?php

namespace App\Http\Controllers\Host;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthorizeController extends Controller
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

    // add comments
    public function check() 
    {
        return response()->json(['message' => 'Authorized.']);
    }
}
