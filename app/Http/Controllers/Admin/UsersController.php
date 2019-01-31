<?php

namespace App\Http\Controllers\Admin;

use App\Entity\User;
use App\Http\Requests\Admin\CreateRequest;
use App\Http\Requests\Admin\UpdateRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{

    public function index()
    {
     $users=User::orderBy('id','desc')->paginate(20);
     return view('admin.users.index',compact('users'));
    }


    public function create()
    {
        return view('admin.users.create');
    }


    public function store(CreateRequest $request)
    {

        $user=User::create($request->only('name','email')+
            [
                'password'=>Hash::make(Str::random()),
                'status'=>User::STATUS_ACTIV,
            ]
        );

        return redirect()->route('admin.users.show',['id'=>$user->id]);

    }


    public function show(User $user)
    {
      return view('admin.users.show',compact('user'));
    }


    public function edit(User $user)
    {

      return view('admin.users.edit',compact('user'));
    }


    public function update(UpdateRequest $request, User $user)
    {

        $user->update($request->only('name','email','status'));
       return redirect()->route('admin.users.show',$user);
    }


    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index');
    }

    public function verify(User $user){
        $user->verify();
        return redirect()->route('admin.users.show',$user);
    }
}
