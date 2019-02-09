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
    return view('admin.adverts.categories.index',compact('categories'));

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
        return view('admin.adverts.categories.edit',compact('category','parents'));
    }

    public function update(Request $request,Category $category)
    {
        $this->validate($request,[
            'name'=>'required|string|max:255',
            'slug'=>'required|string|max:255',
            'parent'=>'nullable|inreger|esiwtw:advert_categories,id',
        ]);
        $category->update([
            'name'=>$request['name'],
            'slug'=>$request['slug'],
            'parent'=>$request['parent'],
        ]);

        return redirect()->route('admin.adverts.categories.show',$category);

    }
    public function destroy(Category $category){
        $category->delete();
        return redirect()->route('admin.adverts.categories.index');

    }
    public function first(Category $category)
    {
        if ($first=$category->siblings()->defaultOrder()->first()){
            $category->insertBeforeNode($first);
        }
        return redirect()->route('admin.adverts.categories.index');
    }
    public function up (Category $category)
    {
        $category->up();
        return redirect()->route('admin.adverts.categories.index');
    }

    public function down(Category $category)
    {
        $category->down();
        return redirect()->route('admin.adverts.categories.index');
    }

    public function last(Category $category)
    {
        if ($last=$category->siblings()->defaultOrder('desc')->first()){
            $category->insertAfterNode($last);
        }
        return redirect()->route('admin.adverts.categories.index');
    }




}