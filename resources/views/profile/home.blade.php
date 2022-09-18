@extends('layouts.app')
@section('content')
    <div class="container">
        @can('update', $user->profile)
            <home-component :user="{{ $user }}" :profile="{{ $user->profile }}" :posts="{{ $user->posts }}"
                canview="{{ true }}" follows="{{ $follows }}" followercount="{{ $followerCount }}" 
                followingcount="{{ $followingCount }}">
            </home-component>
        @else
            <home-component :user="{{ $user }}" :profile="{{ $user->profile }}" :posts="{{ $user->posts }}"
                canview="{{ false }}" follows="{{ $follows }}"
                followercount="{{ $user->profile->followers->count() }}" followingcount="{{ $user->following->count() }}">
            </home-component>
        @endcan


    </div>
@endsection
