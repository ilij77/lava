<?php

namespace App\Http\Controllers;

use App\Entity\Adverts\Category;
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
        $regions=Region::roots()->orderBy('name')->getModels();
        $categories=Category::whereIsRoot()->defaultOrder()->getModels();


        return view('home')->with(compact('regions','categories'));
    }
}
