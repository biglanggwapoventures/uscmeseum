@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <h3>Our Best Sellers</h3>
            </div>
        </div>
        <div class="row mb-5">
            @foreach($bestSellersQuantities as $itemId => $quantitySold)
                <div class="col">
                    <div class="card">
                        <div class="card-img-top"
                             style="min-height:100px; background:url({{ asset("storage/{$bestSellerItems->get($itemId)->image_filepath}") }}) center center;background-size:cover">
                            &nbsp;
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $bestSellerItems->get($itemId)->name }}</h5>
                            <dl class="row ">
                                <dt class="col-sm-5">Qty Sold</dt>
                                <dd class="col-sm-7 text-right">{{ number_format($quantitySold) }}</dd>
                                <dt class="col-sm-5">Price</dt>
                                <dd class="col-sm-7 text-right">{{ number_format( $bestSellerItems->get($itemId)->selling_price, 2) }}</dd>
                            </dl>
                            <div class="text-center">
                                <a href="{{ url("{$bestSellerItems->get($itemId)->id}/{$bestSellerItems->get($itemId)->slug}") }}" class="btn btn-block btn-outline-success">View
                                    item</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col">
                <h3>Browse For More</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                @if(session('registered'))
                    <div class="alert alert-success"><i class="fas fa-check"></i> Thank you for signing up! You may wait
                        for your account activation before you are able to login!
                    </div>
                @endif
                <form action="{{ url('/') }}" method="get">
                    <div class="input-group mb-3">
                        @if($categoryId = request()->input('category_id'))
                            <input type="hidden" name="category_id" value="{{ $categoryId }}">
                        @endif
                        <input type="text" name="q" class="form-control form-control-lg"
                               placeholder="Search for an item..." value="{{ request()->input('q') }}">
                        <div class="input-group-append">
                            <button class="btn btn-success" type="submit" id="button-addon2">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="list-group mb-3">
                    <a href="{{ url('/') }}"
                       class="list-group-item list-group-item-action {{ !request()->input('category_id') ? 'active' : '' }}">
                        ALL CATEGORIES
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ url('/'). '?category_id='. $category->id  }}"
                           class="list-group-item list-group-item-action {{ $category->id == request()->input('category_id') ? 'active' : '' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="col-sm-9">
                @forelse($items as $item)
                    <div class="card mb-3">
                        <div class="row no-gutters">
                            <div class="col-md-4"
                                 style="min-height:200px; background:url({{ asset("storage/{$item->image_filepath}") }}) center center;background-size:cover">
                                &nbsp;
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title font-weight-bold"><a class="text-dark text-decoration-none"
                                                                               href="{{ url("{$item->id}/{$item->slug}") }}">{{ $item->name }}</a>
                                    </h5>
                                    <h5 class="card-subtitle text-info mb-4">{{ number_format($item->selling_price, 2) }}</h5>
                                    <p class="card-text">{{ Str::limit($item->description, 200) }}</p>
                                    <a href="{{ url("{$item->id}/{$item->slug}") }}" class="btn btn-primary">View
                                        item</a>
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
