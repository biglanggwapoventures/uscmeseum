@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <h1>Profile</h1>
                <div class="card">
                    <div class="card-body">
                        @if($message = session('message'))
                            <div class="text-center text-success mb-3"><i class="fas fa-check"></i> Profile has been updated!
                            </div>
                        @endif
                        <form method="POST" action="{{ url('profile') }}">
                            @csrf
                            @method('put')

                            <div class="form-group row">
                                <label for="firstname" class="col-md-4 col-form-label text-md-right">First Name</label>

                                <div class="col-md-6">
                                    <input id="firstname" type="text"
                                           class="form-control{{ $errors->has('firstname') ? ' is-invalid' : '' }}"
                                           name="firstname" value="{{ old('firstname', $user->firstname) }}" required
                                           autofocus>

                                    @if ($errors->has('firstname'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('firstname') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="lastname" class="col-md-4 col-form-label text-md-right">Last Name</label>

                                <div class="col-md-6">
                                    <input id="lastname" type="text"
                                           class="form-control{{ $errors->has('lastname') ? ' is-invalid' : '' }}"
                                           name="lastname" value="{{ old('lastname', $user->lastname) }}" required>

                                    @if ($errors->has('lastname'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('lastname') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control-plaintext" readonly
                                           value="{{ $user->email }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="contact_number" class="col-md-4 col-form-label text-md-right">Contact
                                    Number</label>

                                <div class="col-md-6">
                                    <input id="contact_number" type="text"
                                           class="form-control{{ $errors->has('contact_number') ? ' is-invalid' : '' }}"
                                           name="contact_number"
                                           value="{{ old('contact_number', $user->contact_number) }}" required>

                                    @if ($errors->has('contact_number'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('contact_number') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                           class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                           name="password">

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm
                                    Password</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                           name="password_confirmation">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Update Profile
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
