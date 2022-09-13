@extends('layouts.app')
@section('content')
<div class="container">
    <div class="container">
        <form action="/p/{{$user->user_id}}" enctype="multipart/form-data" method="POST">
        @csrf 
        @method('PATCH')
        {{-- overrides the form method --}}
            <div class="row">
            <div class="col-8 offset-2">
              <div class="row">
                <h1>Edit Profile</h1>
              </div>
    
              <div class="row mb-3">
                <label for="title" class="col-md-4 col-form-label">
                  Title
                </label>
    
                <input
                  id="title"
                  type="text"
                  class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}"
                  name="title"
                  value="{{old('title') ?? $user->title}}"
                  {{-- required --}}
                  autocomplete="title"
                  autofocus
                />
                @if ($errors->has('title'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('title') }}</strong>
                </span> 
                @endif
              </div>
              <div class="row mb-3">
                <label for="description" class="col-md-4 col-form-label">
                    Description
                </label>
                <input
                id="description"
                type="text"
                class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}"
                name="description"
                value="{{old('description') ??$user->description}}"
                {{-- required --}}
                autocomplete="description"
                autofocus
              />
                @if ($errors->has('description'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{  $errors->first('description') }}</strong>
                </span> 
                @endif
              </div>
              <div class="row mb-3">
                <label for="url" class="col-md-4 col-form-label">
                  Url
                </label>
                <input
                  id="url"
                  type="text"
                  class="form-control {{ $errors->has('url') ? ' is-invalid' : '' }}"
                  name="url"
                  value="{{old('url') ??$user->url}}"
                  {{-- required --}}
                  autocomplete="url"
                  autofocus
                />
                @if ($errors->has('url'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{  $errors->first('url') }}</strong>
                </span> 
                @endif
              </div>
              <div class="row">
                <label for="image" class="col-md-4 col-form-label">
                  Profile Picture
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
                    <button class="btn btn-primary">Update Profile</button>
                </div>
                </div>
            </div>
          </div>
        </form>
      </div>
 </div>
@endsection