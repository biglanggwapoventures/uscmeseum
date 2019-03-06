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

