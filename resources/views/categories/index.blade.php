@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row align-items-center mb-3">
        <div class="col-sm-6">
            <h3>Categories</h3>
        </div>
        <div class="col-sm-6 text-right">
            <a href="{{ url('admin/categories/create') }}" class="btn btn-primary">Create new category</a>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
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
                                <th></th>
                                <th>Name</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td>
                                        <img src="{{ asset("storage/{$category->image_filepath}") }}" alt="{{ $category->name }}" style="height:50px;width:50px">
                                    </td>
                                    <td>{{ $category->name }}</td>
                                    <td class="vertical-align-middle">
                                        <a href="{{ url("admin/categories/{$category->id}/edit") }}" class="btn btn-sm btn-info mr-2">Edit</a>
                                        <form action="{{ url("admin/categories/{$category->id}") }}" method="post" onsubmit="return confirm('Are you sure you want to delete this category? This cannot be undone.')">
                                            @csrf
                                            @method('delete')
                                            <button type="submit"class="btn btn-outline-danger btn-sm ">Delete</button>
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

