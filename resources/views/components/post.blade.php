<div class="container">
    <div class="row mt-5">
        @foreach ($posts as $post )
        <div class="col-4 mt-3 mb-3">
            <a href="/p/{{$post->id}}">
                <img src="/storage/{{$post->image}}" class="post-img" alt="" />
            </a>
        </div>
        @endforeach
        @if ($posts)
        <div class="cols-12 d-flex justify-content-center">
            {{$posts->links()}}
        </div>
        @endif
    </div>
</div>