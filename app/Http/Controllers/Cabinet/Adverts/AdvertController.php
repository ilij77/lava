<?php
/**
 * Created by PhpStorm.
 * User: Илья
 * Date: 16.02.2019
 * Time: 1:52
 */

namespace App\Http\Controllers\Cabinet\Adverts;


use App\Entity\Adverts\Advert\Advert;
use App\Http\Controllers\Controller;
use App\Http\Middleware\FilledProfile;
use Illuminate\Support\Facades\Auth;

class AdvertController extends Controller

{
    public function __construct()
    {
        $this->middleware(FilledProfile::class);
    }

    public function index()
    {
        $adverts=Advert::forUser(Auth::user())->orderByDesc('id')->paginate(20);

        return view('cabinet.adverts.index',compact('adverts'));
    }

    public function create()
    {
        return view('cabinet.adverts.create');
    }

    public function edit()
    {
        return view('cabinet.adverts.edit');
    }

}