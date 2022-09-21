<x-layout>
    <post-component :post="{{$post}}" :user="{{$post->user}}" image="{{$post->user->profile->image}}"
        current="{{Auth::user()->id }}" follows="{{$follows}}" like="{{$likedPost}}" likeCount="{{$likeCount}}">
    </post-component>
</x-layout>