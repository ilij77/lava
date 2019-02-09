@extends('layouts.app')

@section('content')
    @include('admin.adverts.categories._nav')

    <div class="d-flex flex-row mb-3">
        <a href="{{ route('admin.adverts.categories.edit', $category) }}" class="btn btn-primary mr-1">Edit</a>
        <form method="POST" action="{{ route('admin.adverts.categories.destroy', $category) }}" class="mr-1">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Delete </button>
        </form>
    </div>

    <table class="table table-bordered table-striped">
        <tbody>
        <tr>
            <th>ID</th><td>{{ $category->id }}</td>
        </tr>
        <tr>
            <th>Name</th><td>{{ $category->name }}</td>
        </tr>
        <tr>
            <th>Slug</th><td>{{ $category->slug }}</td>
        </tr>
        <tbody>
        </tbody>
    </table>

    <p><a href="#" class="btn btn-success">Add Attribute</a></p>

    <table class="table table-bordered">
        <thead>
        <tr>

            <th>Name</th>
            <th>Slug</th>

        </tr>
        </thead>
        <tbody>




            <tr>

                <td>{{ $category->name }}</td>
                <td>{{ $category->slug }}</td>

            </tr>


        </tbody>
    </table>
@endsection