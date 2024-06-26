@section('title')
Edit Profile
@endsection
<x-layout>
  <div class="container">
    <div class="container">
      <form action="/profile/{{$user->profile->user_id}}" enctype="multipart/form-data" method="POST">
        @csrf
        @method('PUT')
        {{-- overrides the form method --}}
        <div class="row">
          <div class="col-8 offset-2">
            <div class="row">
              <h1>Edit Profile</h1>
            </div>

            <div class="row mb-3">
              <label for="title" class="col-md-4 col-form-label fw-bold">
                Title
              </label>

              <input id="title" type="text" class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}"
                name="title" value="{{old('title') ?? $user->profile->title}}" {{-- required --}} autocomplete="title"
                autofocus />
              @if ($errors->has('title'))
              <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('title') }}</strong>
              </span>
              @endif
            </div>
            <div class="row mb-3">
              <label for="description" class="col-md-4 col-form-label fw-bold">
                Description
              </label>
              <input id="description" type="text"
                class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}" name="description"
                value="{{old('description') ??$user->profile->description}}" autocomplete="description" autofocus />
              @if ($errors->has('description'))
              <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('description') }}</strong>
              </span>
              @endif
            </div>
            <div class="row mb-4">
              <label for="url" class="col-md-4 col-form-label fw-bold">
                Url
              </label>
              <input id="url" type="text" class="form-control {{ $errors->has('url') ? ' is-invalid' : '' }}" name="url"
                value="{{old('url') ??$user->profile->url}}" autocomplete="url" autofocus />
              @if ($errors->has('url'))
              <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('url') }}</strong>
              </span>
              @endif
            </div>
            <div class="row mb-3">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked"
                  style="cursor: pointer" name="is_private" @if($user->profile->is_private) checked
                @endif>
                <label class="form-check-label fw-bold" for="flexSwitchCheckChecked">Make Account Private</label>
              </div>
            </div>
            <div class="row">
              <label for="image" class="col-md-4 col-form-label fw-bold">
                Profile Picture
              </label>
              <input type="file" class="form-control" id="image" name="image" />
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
</x-layout>