<?php

namespace App\Http\Controllers\Admin;

use App\Entity\Region;
use App\Entity\User;
use App\Http\Requests\Admin\CreateRequest;
use App\Http\Requests\Admin\UpdateRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\UseCases\Auth\RegisterService;

class RegionController extends Controller
{


    public function index(Request $request)

    {
        $regions=Region::orderBy('name')->get();


     return view('admin.regions.index',compact('regions'));
    }


    public function create(Request $request)
    {

        return view('admin.regions.create');
    }


    public function store(Request $request)
    {

        $this->validate($request,[
            'name'=>'required|string|max:255|unique:regions,name,NULL,id,parent_id' .($request['parent'] ?:'NULL'),
            'slug'=>'required|string|max:255|unique:regions,name,NULL,id,parent_id' .($request['parent'] ?:'NULL'),
            'parent'=>'optional|exists:exists,id'
        ]);
        $region=Region::create([
            'name'=>$request['name'],
            'slug'=>$request['slug'],
            'parent_id'=>$request['parent'],
        ]);

        return redirect()->route('admin.regions.show');

    }


    public function show(Region $region)
    {
        $regions=Region::where('parent_id',$region->id)->orderBy('name')->gen();
      return view('admin.regions.show',compact('region','regions'));
    }


    public function edit(Region $region)
    {

      return view('admin.regions.edit',compact('region'));
    }


    public function update(Request $request, Region $region)
    {


        $this->validate($request,[
            'name'=>'required|string|max:255|unique:regions,name,NULL,id,parent_id' .($request['parent'] ?:'NULL'),
            'slug'=>'required|string|max:255|unique:regions,name,NULL,id,parent_id' .($request['parent'] ?:'NULL'),

        ]);
        $region->update([
            'name'=>$request['name'],
            'slug'=>$request['slug'],

        ]);

       return redirect()->route('admin.regions.show',$region);
    }


    public function destroy(Region $region)
    {
        $region->delete();
        return redirect()->route('admin.regions.index');
    }


}
