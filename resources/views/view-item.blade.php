@extends('layouts.app')

@push('css')
    <style type="text/css">
        .favorite {
            cursor: pointer;
        }
    </style>
@endpush

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
            <div class="alert alert-success">
                <i class="fas fa-check"></i> {{ $message }}
                <hr>
                <p class="mb-0">
                    <a href="{{ url('cart') }}" class="btn btn-outline-success"><i class="fas fa-shopping-cart"></i> Go
                        to cart</a>
                    <span class="mx-3">or</span>
                    <a href="{{ url('/') }}" class="btn btn-outline-success"><i class="fas fa-plus"></i> Add more items</a>
                </p>
            </div>
        @endif
        <div class="row">
            <div class="col-sm-4 text-center">
                <img src="{{ asset("storage/$item->image_filepath") }}" alt="" class="img-fluid mx-auto mb-3">
                @auth
                    @if(auth()->user()->hasFavorite($item->id))
                        <p class="text-danger favorite"><i class="fas fa-heart"> </i> This item is in your favorites
                            list</p>
                    @else
                        <p class="text-info favorite"><i class="far fa-heart"></i> Mark as favorite</p>
                    @endif
                @endauth
            </div>
            <div class="col sm-9">
                <h1>{{ $item->name }}</h1>
                <h3 class="text-success">
                    <i class="fas fa-money-bill"></i>
                    {{ number_format($item->selling_price, 2) }}
                </h3>
                <p>{{ $item->description }}</p>
                @if($item->attributes)
                    <dl class="row ">
                        @foreach($item->attributes as $attribute)
                            <dt class="col-sm-3">{{ $attribute->name }}</dt>
                            <dd class="col-sm-9">{{  $attribute->pivot->value }}</dd>
                        @endforeach
                    </dl>

                @endif
                <hr>
                @auth
                    @if(Cart::has($item->id))
                        <p class="alert alert-info text-center"><i class="fas fa-check"></i> This item is already in
                            your cart. Go to <a href="{{ url('cart') }}">checkout</a> now!</p>
                    @else
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
                                               aria-label="Example text with button addon"
                                               aria-describedby="button-addon1">
                                        @if($errors->has('quantity'))
                                            <p class="text-danger">* {{ $errors->first('quantity') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <button type="submit" class="btn btn-success btn-lg btn-block"><i
                                                class="fas fa-plus"></i>
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif

                @else
                    <p class="text-center text-danger">
                        Please login to checkout items
                    </p>
                @endif
            </div>
        </div>
    </div>
@endsection


@push('js')
    <script type="text/javascript">
      $(document).ready(function () {
        $('.favorite').click(function () {
          var $this = $(this);
          $.post("{{ url("item/{$item->id}/favorite") }}", {
            _token: "{{ csrf_token() }}"
          }).done(function () {
            window.location.reload()
          })
        })
      })
    </script>
@endpush