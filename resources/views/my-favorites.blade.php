@extends('layouts.app')

@section('content')
    <div class="container">
            <div class="col-sm-8 offset-sm-2">
                <h1>My Favorite Items</h1>
                @forelse($items as $item)
                    <div class="card mb-3">
                        <div class="row no-gutters">
                            <div class="col-md-4" style="min-height:200px; background:url({{ asset("storage/{$item->image_filepath}") }}) center center;background-size:cover">
                                &nbsp;
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title font-weight-bold"><a class="text-dark text-decoration-none" href="{{ url("{$item->id}/{$item->slug}") }}">{{ $item->name }}</a></h5>
                                    <h5 class="card-subtitle text-info mb-4">{{ number_format($item->selling_price, 2) }}</h5>
                                    <p class="card-text">{{ Str::limit($item->description, 200) }}</p>
                                    <a href="{{ url("{$item->id}/{$item->slug}") }}" class="btn btn-primary">View item</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info text-center">
                        No items found.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
