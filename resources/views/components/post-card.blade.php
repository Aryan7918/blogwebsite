<div class="col-md-6">
    <div class="card mb-4">
        <div class="card-body">
            <h2 class="card-title h4">{{Str::substr($post->title, 0, 50)}}...</h2>
            <p class="card-text mb-1">{{Str::substr($post->slug, 0, 100)}}...</p>
            <div class="small text-muted">{{ \Carbon\Carbon::parse($post->published_at)->diffForHumans()}}</div>
            <a class="btn btn-primary" href="{{url('posts/'.$post->slug)}}">Read more →</a>
        </div>
    </div>
</div>