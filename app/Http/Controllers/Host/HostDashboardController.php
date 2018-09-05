<?php

namespace App\Http\Controllers\Host;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HostDashboardController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    	$this->middleware(['auth', 'role:host']);
    }

    public function index()
    {
    	return response()->json(['hello' => 'world']);
    }
}
