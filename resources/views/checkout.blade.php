@extends('layouts.app')
@push('css')
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <h1>Checkout</h1>



                <div class="card">
                    <div class="card-body">
                        @if($creditCardError = session('creditCardError'))
                            <div class="alert alert-danger"><i class="fas fa-info-circle"></i> {{ $creditCardError }}
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-4 order-md-2 mb-4">
                                <ul class="list-group mb-3">
                                    @php $totalAmount = 0 @endphp
                                    @foreach($products as $item)
                                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                                            <div>
                                                <h6 class="my-0">{{ $item['product']['name'] }}</h6>
                                                <small class="text-muted">x {{ $item['quantity'] }} unit(s)</small>
                                            </div>
                                            <span class="text-muted">
                                                @php
                                                    $total = $item['quantity'] * $item['product']->selling_price;
                                                    $totalAmount += $total;
                                                @endphp
                                                {{ number_format($total, 2) }}
                                             </span>
                                        </li>
                                    @endforeach
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Total (PHP)</span>
                                        <strong>{{ number_format($totalAmount, 2) }}</strong>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-8 order-md-1">
                                <h4 class="mb-3">Billing address</h4>
                                <form action="{{ url('checkout') }}" method="POST" class="ajax">
                                    @csrf
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text"
                                               class="form-control{{ $errors->has('billingAddress1') ? ' is-invalid' : '' }}"
                                               name="billingAddress1" value="{{ old('billingAddress1') }}"
                                               placeholder="1234 Main St">
                                        @if ($errors->has('billingAddress1'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('billingAddress1') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 mb-3">
                                            <div class="form-group">
                                                <label>City</label>
                                                <input type="text"
                                                       class="form-control{{ $errors->has('billingCity') ? ' is-invalid' : '' }}"
                                                       name="billingCity" value="{{ old('billingCity') }}">
                                                @if ($errors->has('billingCity'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('billingCity') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="form-group">
                                                <label>Zip Code</label>
                                                <input type="text"
                                                       class="form-control{{ $errors->has('billingPostcode') ? ' is-invalid' : '' }}"
                                                       name="billingPostcode" value="{{ old('billingPostcode') }}">
                                                @if ($errors->has('billingPostcode'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('billingPostcode') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <h4 class="mb-3">Credit Card Information</h4>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="form-group">
                                                <label>First Name</label>
                                                <input type="text"
                                                       class="form-control{{ $errors->has('firstName') ? ' is-invalid' : '' }}"
                                                       name="firstName"
                                                       value="{{ old('firstName', auth()->user()->firstname) }}">
                                                @if ($errors->has('firstName'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('firstName') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="form-group">
                                                <label>Last Name</label>
                                                <input type="text"
                                                       class="form-control{{ $errors->has('lastName') ? ' is-invalid' : '' }}"
                                                       name="lastName"
                                                       value="{{ old('lastName', auth()->user()->lastname) }}">
                                                @if ($errors->has('lastName'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('lastName') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="form-group">
                                                <label>Credit Card Number</label>
                                                <input type="text"
                                                       class="form-control{{ $errors->has('number') ? ' is-invalid' : '' }}"
                                                       name="number" value="{{ old('number') }}">
                                                @if ($errors->has('number'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('number') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">Expiry</label>
                                            <div class="form-row ">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <select name="expiryMonth"
                                                                class="form-control{{ $errors->has('expiryMonth') ? ' is-invalid' : '' }}">
                                                            <option selected></option>
                                                            @foreach($monthOptions AS $key => $text)
                                                                <option value="{{ $key }}" {{ old('expiryMonth') == $key ? 'selected="selected"' : '' }}>{{ $text }}</option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('expiryMonth'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('expiryMonth') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <select name="expiryYear"
                                                                class="form-control{{ $errors->has('expiryYear') ? ' is-invalid' : '' }}">
                                                            <option selected></option>
                                                            @foreach($yearOptions AS $key => $text)
                                                                <option value="{{ $key }}" {{ old('expiryYear') == $key ? 'selected="selected"' : '' }}>{{ $text }}</option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('expiryYear'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('expiryYear') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="form-group">
                                                <label>CVV</label>
                                                <input type="text"
                                                       class="form-control{{ $errors->has('cvv') ? ' is-invalid' : '' }}"
                                                       name="cvv" value="{{ old('cvv') }}">
                                                @if ($errors->has('cvv'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('cvv') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="mb-4">
                                    <button class="btn btn-primary btn-lg btn-block" type="submit">Confirm Checkout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
