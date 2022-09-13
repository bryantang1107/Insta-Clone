@extends('layouts.app')
@section('content')
<div class="container">
    <div class="container">
        <form action="/p" enctype="multipart/form-data" method="POST">
        @csrf 
            <div class="row">
            <div class="col-8 offset-2">
              <div class="row">
                <h1>Add New Post</h1>
              </div>
    
              <div class="row mb-3">
                <label for="caption" class="col-md-4 col-form-label">
                  Image Caption
                </label>
    
                <input
                  id="caption"
                  type="text"
                  class="form-control {{ $errors->has('caption') ? ' is-invalid' : '' }}"
                  name="caption"
                  value=""
                  {{-- required --}}
                  autocomplete="caption"
                  autofocus
                />
                @if ($errors->has('caption'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{  $errors->first('caption') }}</strong>
                </span> 
                @endif
              </div>
              <div class="row">
                <label for="image" class="col-md-4 col-form-label">
                  Post Image
                </label>
                <input
                  type="file"
                  class="form-control-file"
                  id="image"
                  name="image"
                />
                @if ($errors->has('image'))
                  <strong class="mt-3 alert alert-danger">{{ $errors->first('image') }}</strong>
                @endif
              </div>
              <div class="row mt-4">
                <button class="btn btn-primary">Add Post</button>
              </div>
            </div>
          </div>
        </form>
      </div>
 </div>
@endsection