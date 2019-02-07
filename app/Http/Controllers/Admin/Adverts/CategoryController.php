<?php
/**
 * Created by PhpStorm.
 * User: Илья
 * Date: 07.02.2019
 * Time: 2:26
 */

namespace App\Http\Controllers\Admin\Adverts;

use App\Http\Controllers\Controller;
use App\Entity\Adverts\Category;
use Illuminate\Http\Request;


class CategoryController extends Controller
{

    public function index()
{
    $categories=Category::defaultOrder()->withDepth()->get();
    return view('admin.adverts.categories.inde[',compact('categories'));

}

    public function create()
    {
        $parents=Category::defaultOrder()->withDepth()->get();
        return view('admin.adverts.categories.create',compact('parents'));

    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required|string|max:255',
            'slug'=>'required|string|max:255',
            'parent'=>'nullable|inreger|esiwtw:advert_categories,id',
        ]);
        $category=Category::create([
            'name'=>$request['name'],
            'slug'=>$request['slug'],
            'parent'=>$request['parent'],
        ]);

        return redirect()->route('admin.adverts.categories.show',$category);

    }
    public function show(Category $category)
    {
        return view('admin.adverts.categories.show',compact('category'));
    }
    public function edit(Category $category)
    {
        $parents=Category::defaultOrder()->withDepth()->get();
    }


}