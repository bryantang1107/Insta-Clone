@section('title')
Home
@endsection
<x-layout>
    <div class="container">
        <welcome-component class="mb-5"></welcome-component>
        @foreach ($posts as $post)
        @if($likedPost->contains($post->id))
        <post-component :post="{{$post}}" :user="{{$post->user}}" image="{{$post->user->profile->image}}"
            current="{{auth()->user()->id }}" :follows={{true}} like={{true}} likecount={{$post->likes->count()}}
            postDate="{{$post->created_at->diffForHumans()}}" commentCount="{{$post->comments->count()}}">
        </post-component>
        @else
        <post-component :post="{{$post}}" :user="{{$post->user}}" image="{{$post->user->profile->image}}"
            current="{{auth()->user()->id }}" :follows={{true}} likecount={{$post->likes->count()}}
            postDate="{{$post->created_at->diffForHumans()}}" commentCount="{{$post->comments->count()}}">
        </post-component>
        @endif
        @endforeach
        @if ($posts)

        <div class="row">
            <div class="cols-12 d-flex justify-content-center">
                {{$posts->links()}}
                {{-- this gets added when we call paginate in our controller --}}
            </div>
            @endif

        </div>
    </div>
</x-layout>