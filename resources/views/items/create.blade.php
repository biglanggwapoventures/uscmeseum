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
                        Create new item
                    </h5>
                    <form method="post" action="{{ url('admin/items') }}" enctype="multipart/form-data" class="ajax">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Category</label>
                            <div class="col-sm-9">
                                <select name="category_id" class="form-control{{ $errors->has('category_id') ? ' is-invalid' : '' }}">
                                    <option selected></option>
                                    @foreach($categories AS $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected="selected"' : '' }}>{{ $category->name }}</option>
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
                                <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}">
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
                                <textarea rows="3" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" id="description">{{ old('description') }}</textarea>
                                @if ($errors->has('description'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                           
                        </div>
                        <hr>
                        <table class="table" id="category-attributes">
                            <thead class="thead-dark">
                            <tr>
                                <th>Attribute</th>
                                <th>Unique</th>
                                <th>Required</th>
                                <th>Value</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <hr>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Selling Price</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control{{ $errors->has('selling_price') ? ' is-invalid' : '' }}" name="selling_price" value="{{ old('selling_price') }}">
                                @if ($errors->has('selling_price'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('selling_price') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Reorder Level</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control{{ $errors->has('reorder_level') ? ' is-invalid' : '' }}" min="0" name="reorder_level" value="{{ old('reorder_level') }}">
                                @if ($errors->has('reorder_level'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('reorder_level') }}</strong>
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




@push('js')
    <script type="text/javascript">
      $(document).ready(function () {
        let categories = {!! $categories->keyBy('id')->toJson() !!};
        $('[name=category_id]').change(function () {
          var $this= $(this),
            categoryId = $this.val();
          if(!categoryId){
            return;
          }

          $('#category-attributes tbody').html(function () {

            var content = ''
            for(x = 0; x < categories[categoryId].attributes.length; x++){
              content += '<tr>' +
                '<td>'+categories[categoryId].attributes[x].name+'</td>' +
                '<td>'+(categories[categoryId].attributes[x].is_unique ? 'YES' : 'NO' )+'</td>' +
                '<td>'+(categories[categoryId].attributes[x].is_required ? 'YES' : 'NO' )+'</td>' +
                '<td><input class="form-control" type="text" name="attributes['+x+'][value]"><input type="hidden" value="'+categories[categoryId].attributes[x].id+'" name="attributes['+x+'][attribute_id]"></td>'
                '</tr>'
            }
            return content
          })


        })
      });
    </script>
@endpush
