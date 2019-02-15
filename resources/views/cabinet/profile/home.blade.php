@extends('layouts.app')
@section('content')
    @include('cabinet.profile._nav')
    <div class="mb-3">
        <a href="{{route('cabinet.profile.edit')}}" class="btn btn-primary">Edit</a>
    </div>
    <table class="table table-bordered">
        <tbody>
        <tr>
            <th>First Name</th><td>{{$user->name}}</td>
        </tr>
        <tr>
            <th>Last Name</th><td>{{$user->last_name}}</td>
        </tr>
        <tr>
            <th>Phone</th><td>{{$user->phone}}
            @if($user->phone)

                @if(!$user->isPhoneVerified())
                <i>(is not verified)</i><br/>
                <form method="post" action="{{route('cabinet.profile.phone')}}">
                @csrf
                    <button type="submit" class="btn btn-sm btn-success">Verify</button>
            </form>
                @endif
                @endif</td>
        </tr>
        <tr>
            <th>Two Factor Auth</th>
            <td>
                <form action="{{route('cabinet.profile.phone.auth')}}" method="post">
                    @csrf
                    @if($user->isPhoneAuthEnabled())
                        <button type="submit" class="btn btn-sm btn-success">On</button>
                        @else
                        <button type="submit" class="btn btn-sm btn-danger">Off</button>
                        @endif
                </form>
            </td>
        </tr>

        <tr>
            <th>Email</th><td>{{$user->email}}</td>
        </tr>

        </tbody>

    </table>
@endsection