@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-sm-10 offset-sm-1">
                <h1>Most Favorited Items</h1>
                <div class="card">
                    <div class="card-body p-0">
                        <table class="table table-hover table-stiped bg-white mb-0">
                            <thead class="thead-dark">
                                <tr>
                                    <th></th>
                                    <th>Favorite Count</th>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th class="text-right">Selling Price</th>
                                    <th class="text-right">Stock on hand</th>

                                </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td class="text-center">
                                        @if($loop->first && $item->likers_count)
                                            <i class="fas fa-2x fa-crown text-success"></i>
                                        @endif
                                    </td>
                                    <td class="font-weight-bold text-center vertical-align-middle">

                                        {{ $item->likers_count }}
                                    </td>
                                    <td>
                                        <img src="{{ asset("storage/{$item->image_filepath}") }}" alt="{{ $item->name }}" style="height:50px;width:50px">
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->category->name }}</td>
                                    <td class="text-right">{{ number_format($item->selling_price, 2) }}</td>
                                    <td class="text-right">{{ number_format($item->balance) }}</td>
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

