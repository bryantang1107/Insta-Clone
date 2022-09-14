@extends('layouts.app')

@section('content')
    <post-component :post="{{$post}}" :user="{{$post->user}}" image="{{$post->user->profile->image}}" current="{{Auth::user()->id }}" follows="{{$follows}}"></post-component>
@endsection