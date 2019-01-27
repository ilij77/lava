@extends('layouts.app')
@section('content')
    @include('admin.users._nav')
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>

        </tr>
        </thead>
        <tbody>

        @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td><a href="{{ route('admin.users.show', $user->id) }}">{{ $user->name }}</a></td>
                <td>{{ $user->email }}</td>
                <td>
                    @if ($user->status===\App\Entity\User::STATUS_WAIT)
                        <span class="badge badge-secondary">Waiting</span>
                    @endif
                    @if ($user->status===\App\Entity\User::STATUS_ACTIV)
                        <span class="badge badge-primary">Active</span>
                    @endif
                </td>

            </tr>
        @endforeach

        </tbody>
    </table>
    {{ $users->links() }}
@endsection

