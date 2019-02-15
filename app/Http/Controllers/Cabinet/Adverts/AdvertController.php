<?php
/**
 * Created by PhpStorm.
 * User: Илья
 * Date: 16.02.2019
 * Time: 1:52
 */

namespace App\Http\Controllers\Cabinet\Adverts;


use App\Http\Controllers\Controller;
use App\Http\Middleware\FilledProfile;

class AdvertController extends Controller

{
    public function __construct()
    {
        $this->middleware(FilledProfile::class);
    }

    public function index()
    {
        return view('cabinet.adverts.index');
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