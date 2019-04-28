@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row align-items-center mb-3">
            <div class="col-sm-6">
                <h3>Categories</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            Create new category
                        </h5>
                        <form method="post" action="{{ url('admin/categories') }}" enctype="multipart/form-data"
                              class="ajax">
                            @csrf
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Name</label>
                                <div class="col-sm-9">
                                    <input type="text"
                                           class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           name="name" value="{{ old('name') }}">
                                </div>
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                            <hr>
                            <h5>Attributes</h5>
                            <table class="table dynamic-table">
                                <thead class="thead-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Required?</th>
                                    <th>Unique?</th>
                                    <th></th>
                                </tr>

                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" name="attributes[0][name]"
                                               data-name="attributes[idx][name]">
                                    </td>
                                    <td>
                                        <label>
                                            <input type="checkbox" name="attributes[0][is_required]"
                                                   data-name="attributes[idx][is_required]" value="1"> Yes
                                        </label>
                                    </td>
                                    <td>
                                        <label><input type="checkbox" name="attributes[0][is_unique]"
                                                      data-name="attributes[idx][is_required]" value="1"> Yes</label>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-danger remove-line">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td>
                                        <a href="#" class="btn btn-primary add-line"><i class="fas fa-plus"></i> Add new
                                            line</a></td>
                                    <td colspan="3"></td>
                                </tr>
                                </tfoot>
                            </table>
                            <hr>
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
                                <a href="{{ url('admin/categories') }}" class="btn btn-outline-info">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection