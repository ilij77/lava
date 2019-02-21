<?php

namespace App\Http\Controllers\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Http\Controllers\Controller;


class AdvertController extends Controller
{


    public function index(Region $region=null,Category $category=null)
    {
        $query=Advert::with(['category','region'])->orderBy('id');
        if ($category){
            $query->forCategory($category);
        }
        if ($region){
            $query->forRegion($region);
        }

        $adverts=$query->paginate(20);
        $regions=$region ? $region->children()->orderBy('name')->getModels():
            Region::roots()->orderBy('name')->getModels();
        return view('adverts.index',compact('category','region','adverts'));

    }

    public function show(Advert $advert)
    {
        if (!($advert->isActive()||!$this->isAllowToShow($advert))){
            abort(403);
        }


        return view('adverts.show', compact('advert'));
    }


public function isAllowToShow(Advert $advert)

}
