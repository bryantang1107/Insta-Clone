@section('title')
{{$user->username}}
@endsection
<x-layout>
    <div>
        @auth
        @can('update', $user->profile)
        <home-component :user="{{ $user }}" :posts="{{ $user->posts }}" follows="{{ $follows }}"
            followercount="{{ $followerCount }}" followingcount="{{ $followingCount }}">
        </home-component>
        <x-post :posts="$posts"></x-post>
        @else
        @can('view', $user->profile)
        <home-component :user="{{ $user }}" :posts="{{ $user->posts }}" follows="{{ $follows }}"
            followercount="{{ $followerCount}}" followingcount="{{ $followingCount }}">
        </home-component>
        <x-post :posts="$posts"></x-post>
        @endcan
        @cannot('view', $user->profile)
        <private-profile-component :user="{{ $user }}" :postsLength="{{ $user->posts->count() }}"
            follows="{{ $follows }}" followercount="{{ $followerCount }}" followingcount="{{ $followingCount }}"
            followRequest="{{$followRequest}}">
        </private-profile-component>
        @endcannot
        @endcan
        @endauth

        @guest
        @if ($user->profile->is_private)
        <private-profile-component :user="{{ $user }}" :postsLength="{{ $user->posts->count() }}"
            followercount="{{ $followerCount }}" followingcount="{{ $followingCount }}">
        </private-profile-component>
        @else
        <home-component :user="{{ $user }}" :posts="{{ $user->posts }}" followercount="{{ $followerCount }}"
            followingcount="{{ $followingCount }}">
        </home-component>
        <x-post :posts="$posts"></x-post>
        @endif
        @endguest



    </div>
</x-layout>