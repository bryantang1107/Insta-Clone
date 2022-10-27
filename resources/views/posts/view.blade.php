@section('title')
{{$post->user->username}}
@endsection
<x-layout>
    <post-component :post="{{$post}}" :user="{{$post->user}}" image="{{$post->user->profile->image}}"
        current="{{Auth::user()->id }}" follows="{{$follows}}" like="{{$likedPost}}" likeCount="{{$likeCount}}"
        postDate="{{$post->created_at->diffForHumans()}}" commentCount="{{$commentCount}}">
    </post-component>
</x-layout>