@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <form action="{{ url('/') }}" method="get">
                <div class="input-group mb-3">
                    @if($categoryId = request()->input('category_id'))
                        <input type="hidden" name="category_id" value="{{ $categoryId }}">
                    @endif
                    <input type="text" name="q" class="form-control form-control-lg" placeholder="Search for an item..." value="{{ request()->input('q') }}">
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
                        <div class="col-md-4" style="min-height:200px; background:url({{ asset("storage/{$item->image_filepath}") }}) center center;background-size:cover">
                            &nbsp;
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title font-weight-bold">{{ $item->name }}</h5>
                                <h5 class="card-subtitle text-info mb-4">{{ number_format($item->selling_price, 2) }}</h5>
                                <p class="card-text">{{ Str::limit($item->description, 200) }}</p>
                                <a href="#" class="btn btn-primary">Add to cart</a>
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