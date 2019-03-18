@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row align-items-center mb-3">
        <div class="col-sm-6">
            <h3>Users</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-hover bg-white mb-0">
                        <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Gender</th>
                                <th>Email Address</th>
                                <th>Contact Number</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->firstname }}</td>
                                    <td>{{ $user->lastname }}</td>
                                    <td>{{ $user->gender }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->contact_number }}</td>
                                    <td>
                                        @if($user->enabled_at)
                                            <span class="badge badge-success">Enabled</span>
                                        @else
                                            <span class="badge badge-warning">Pending Activation</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ url("admin/users/{$user->id}/edit") }}" class="btn btn-sm btn-info mr-2">Edit</a>
                                        <a href="#"class="btn btn-danger btn-sm ">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

