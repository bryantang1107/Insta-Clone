@extends('layouts.app')

@section('content')
    <post-component :post="{{$post}}" :user="{{$post->user}}"></post-component>
@endsection