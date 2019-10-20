<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;
use Redirect,Response, Validator;
use Date;

class AjaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        
        $data = Event::all();
        return view('ajax-crud')->withData($data);
    }
    
   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  
        $eventId = $request->event_id;
        //$start = $request->start_date;
        //$end = $request->end_date;
        $event   =   Event::updateOrCreate(['id' => $eventId],
                    ['name' => $request->name, 'start_date' => $request->start_date, 'end_date' => $request->end_date, 'days' => $request->days]);
        //$start = DB::select('SELECT 'start_date' from 'events' where event_id='$eventId'');
        // $end = new DateTime('2013-02-13');
        //$daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
        return Response::json($event);
        //return $post = Post::where('title', 'Post Two')->get();
        //return $post = Post::where('title', 'Post Two')->get();
        //$posts = DB::select('SELECT * FROM posts');
        //$posts = Post::orderBy('title', 'desc')->take(1)->get();
    }
    

}
