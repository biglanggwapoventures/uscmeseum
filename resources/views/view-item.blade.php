@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a
                                    href="{{ url("?category_id={$item->category->id}") }}">{{ $item->category->name }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $item->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
        @if($message = session('message'))
            <div class="alert alert-success"><i class="fas fa-check"></i> {{ $message }}</div>
        @endif
        <div class="row">
            <div class="col-sm-4 text-center">
                <img src="{{ asset("storage/$item->image_filepath") }}" alt="" class="img-fluid mx-auto">
            </div>
            <div class="col sm-9">
                <h1>{{ $item->name }}</h1>
                <h3 class="text-success">
                    <i class="fas fa-money-bill"></i>
                    {{ number_format($item->selling_price, 2) }}
                </h3>
                @if(Auth::user()->hasFavorite($item->id))
                    <h4 class="text-danger"><i class="fas fa-heart"></i></h4>
                @else
                    <h4 class="text-danger"><i class="fas fa-heart"></i></h4>
                @endif
                <p>{{ $item->description }}</p>
                <hr>
                <form action="{{ url('cart') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Quantity</span>
                                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                                    <input type="hidden" name="strategy" value="append">
                                </div>
                                <input name="quantity" value="{{ old('quantity', 1) }}" type="number" min="1b"
                                       class="form-control form-control-lg" placeholder=""
                                       aria-label="Example text with button addon" aria-describedby="button-addon1">
                                @if($errors->has('quantity'))
                                    <p class="text-danger">* {{ $errors->first('quantity') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <button type="submit" class="btn btn-success btn-lg btn-block"><i class="fas fa-plus"></i>
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection