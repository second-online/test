<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sermon;

class SermonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sermons = Sermon::all();

        return response()->json([
            'sermons' => $sermons
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $sermon
     * @return \Illuminate\Http\Response
     */
    public function show(Sermon $sermon)
    {
        return response()->json([
            'sermon' => $sermon
        ]);
    }
}
