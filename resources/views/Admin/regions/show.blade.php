@extends('layouts.app')

@section('content')
@include('admin.regions._nav')
<div class="d-flex flex-row mb-3">
    <a href="{{ route('admin.regions.edit', $region) }}" class="btn btn-primary mr-1">Edit</a>


    <form method="POST" action="{{ route('admin.regions.destroy', $region) }}" class="mr-1">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger">Delete</button>
    </form>
</div>

<table class="table table-bordered table-striped">
    <tbody>
    <tr>
        <th>ID</th><td>{{ $region->id }}</td>
    </tr>
    <tr>
        <th>Name</th><td>{{ $region->name }}</td>
    </tr>
    <tr>
        <th>Slug</th><td>{{ $region->slug }}</td>
    </tr>

    </tbody>
</table>
@include('admin.regions.list',['regions'=>$regions])
@endsection