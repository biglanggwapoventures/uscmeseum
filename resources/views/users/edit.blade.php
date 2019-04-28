@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row align-items-center mb-3">
        <div class="col-sm-6">
            <h3>Users</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        Edit user
                    </h5>
                    <form method="post" action="{{ url("admin/users/{$user->id}") }}">
                        @csrf
                        {{ method_field('PUT') }}
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">First Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control{{ $errors->has('firstname') ? ' is-invalid' : '' }} form-control-plaintext" name="firstname" value="{{ old('firstname', $user->firstname) }}" readonly>
                                @if ($errors->has('firstname'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('firstname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Last Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control{{ $errors->has('lastname') ? ' is-invalid' : '' }} form-control-plaintext" name="lastname" value="{{ old('lastname', $user->lastname) }}" readonly>
                                @if ($errors->has('lastname'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('lastname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Email Address</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} form-control-plaintext"  name="email" value="{{ old('email', $user->email) }}" readonly>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Contact Number</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control{{ $errors->has('contact_number') ? ' is-invalid' : '' }} form-control-plaintext" name="contact_number" value="{{ old('contact_number', $user->contact_number) }}" readonly>
                                @if ($errors->has('contact_number'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('contact_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        <div class="row">
                            <div class="col-sm-9 offset-sm-3">
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="enabled" value="1" {{ old('enabled', $user->enabled_at) ? 'checked="checked"' : '' }}>
                                    <label class="form-check-label" for="exampleCheck1">Enabled</label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-success">Save</button>
                            <a href="{{ url('admin/users') }}" class="btn btn-outline-info">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

