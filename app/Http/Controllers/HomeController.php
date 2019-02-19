<?php

namespace App\Http\Controllers;

use App\Entity\Region;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {


        return view('home')->with(compact('xa'));
    }
}
