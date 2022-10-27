@section('title')
{{$user->username}}
@endsection
<x-layout>
    <div>
        @can('update', $user->profile)
        <home-component :user="{{ $user }}" :profile="{{ $user->profile }}" :posts="{{ $user->posts }}"
            canview="{{ true }}" follows="{{ $follows }}" followercount="{{ $followerCount }}"
            followingcount="{{ $followingCount }}">
        </home-component>
        @else
        @can('view', $user->profile)
        <home-component :user="{{ $user }}" :profile="{{ $user->profile }}" :posts="{{ $user->posts }}"
            canview="{{ false }}" follows="{{ $follows }}" followercount="{{ $user->profile->followers->count() }}"
            followingcount="{{ $user->following->count() }}">
        </home-component>
        @endcan
        @cannot('view', $user->profile)
        @auth
        <private-profile-component :user="{{ $user }}" :profile="{{ $user->profile }}"
            :postsLength="{{ $user->posts->count() }}" follows="{{ $follows }}"
            followercount="{{ $user->profile->followers->count() }}" followingcount="{{ $user->following->count() }}"
            followRequest="{{$followRequest}}">
        </private-profile-component>
        @endauth
        @endcannot
        @endcan

        @guest
        @if ($user->profile->is_private)
        <private-profile-component :user="{{ $user }}" :profile="{{ $user->profile }}"
            :postsLength="{{ $user->posts->count() }}" follows="{{ $follows }}"
            followercount="{{ $user->profile->followers->count() }}" followingcount="{{ $user->following->count() }}">
        </private-profile-component>
        @else
        <home-component :user="{{ $user }}" :profile="{{ $user->profile }}" :posts="{{ $user->posts }}"
            canview="{{ false }}" follows="{{ $follows }}" followercount="{{ $user->profile->followers->count() }}"
            followingcount="{{ $user->following->count() }}">
        </home-component>
        @endif
        @endguest



    </div>
</x-layout>