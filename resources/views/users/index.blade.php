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
                @if(session('deletion'))
                    <div class="alert alert-{{ session('deletion')['variant'] }}">
                        <i class="fas fa-times"></i> {{ session('deletion')['message'] }}
                    </div>
                @endif
                <div class="card">
                    <div class="card-body p-0">
                        <table class="table table-hover bg-white mb-0">
                            <thead>
                            <tr>
                                <th>ID</th>
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
                                    <td>{{ str_pad($user->id, 4, "0", STR_PAD_LEFT) }}</td>
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
                                        <a href="{{ url("admin/users/{$user->id}/edit") }}"
                                           class="btn btn-sm btn-outline-info mr-2">Edit</a>
                                        <form action="{{ url("admin/users/{$user->id}") }}" method="post"
                                              onsubmit="return confirm('Are you sure you want to delete this user? This cannot be undone.')">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-outline-danger btn-sm ">Delete</button>
                                        </form>

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

