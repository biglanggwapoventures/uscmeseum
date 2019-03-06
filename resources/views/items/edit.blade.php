@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row align-items-center mb-3">
        <div class="col-sm-6">
            <h3>Items</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        Edit item
                    </h5>
                    <form method="post" action="{{ url("admin/items/{$item->id}") }}" enctype="multipart/form-data">
                        @csrf
                        {{ method_field('PUT') }}
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Category</label>
                            <div class="col-sm-9">
                                <select name="category_id" class="form-control{{ $errors->has('category_id') ? ' is-invalid' : '' }}">
                                    <option selected></option>
                                    @foreach($categories AS $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected="selected"' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('category_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('category_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                            
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', $item->name) }}">
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Description</label>
                            <div class="col-sm-9">
                                <textarea rows="3" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" id="description">{{ old('description', $item->description) }}</textarea>
                                @if ($errors->has('description'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                           
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Selling Price</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control{{ $errors->has('selling_price') ? ' is-invalid' : '' }}" name="selling_price" value="{{ old('selling_price', $item->selling_price) }}">
                                @if ($errors->has('selling_price'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('selling_price') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Upload image</label>
                            <div class="col-sm-9">
                                <input type="file" name="image">
                                @if ($errors->has('image'))
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $errors->first('image') }}</strong>
                                    </span>
                                @endif
                            </div>
                            
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-success">Save</button>
                            <a href="{{ url('admin/items') }}" class="btn btn-outline-info">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

