@extends('layouts.app')
@section('content')
<div class="container">
   <home-component username="{{$user->username}}" :profile="{{$user->profile}}"
   >
   </home-component>
</div>
@endsection
