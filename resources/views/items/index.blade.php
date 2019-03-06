@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row align-items-center mb-3">
        <div class="col-sm-6">
            <h3>Items</h3>
        </div>
        <div class="col-sm-6 text-right">
            <a href="{{ url('admin/items/create') }}" class="btn btn-primary">Create new item</a>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8">
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-hover bg-white mb-0">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Category</th>
                                <th class="text-right">Selling Price</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td>
                                        <img src="{{ asset("storage/{$item->image_filepath}") }}" alt="{{ $item->name }}" style="height:50px;width:50px">
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->category->name }}</td>
                                    <td class="text-right">{{ number_format($item->selling_price, 2) }}</td>
                                    <td class="text-center">
                                        <a href="{{ url("admin/items/{$item->id}/edit") }}" class="btn btn-sm btn-info mr-2">Edit</a>
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

