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
            <div class="col-sm-12">
                @if($message = session('message'))
                    <div class="text-success">
                        <i class="fas fa-check"></i> {{ $message }}
                    </div>
                @endif
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
                                <th>Category</th>
                                <th class="text-right">Selling Price</th>
                                <th class="text-right">Stock on hand</th>
                                <th class="text-right">Reorder Level</th>
                                <th>Status</th>
                                <th>Logs</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td>
                                        <img src="{{ asset("storage/{$item->image_filepath}") }}"
                                             alt="{{ $item->name }}" style="height:50px;width:50px">
                                    </td>
                                    <td>
                                        {{ $item->name }}
                                    </td>
                                    <td>{{ $item->category->name }}</td>
                                    <td class="text-right">{{ number_format($item->selling_price, 2) }}</td>
                                    <td class="text-right">{{ number_format($item->balance) }}</td>
                                    <td class="text-right">{{ number_format($item->reorder_level) }}</td>
                                    <td>
                                        @if($item->reorder_level <= $item->balance)
                                            <span class="text-danger"><i class="fas fa-info-circle"></i> Low in stock</span>
                                        @else
                                            <span class="text-success"><i class="fas fa-check"></i> High in stock</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ url("admin/item/{$item->id}/logs") }}"
                                           class="btn btn-sm btn-outline-secondary">View logs</a>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ url("admin/items/{$item->id}/edit") }}"
                                           class="btn btn-sm btn-outline-info mr-2">Edit</a>
                                        <form action="{{ url("admin/items/{$item->id}") }}" method="post"
                                              onsubmit="return confirm('Are you sure you want to delete this item? This cannot be undone.')">
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

