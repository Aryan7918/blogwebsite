<div class="card mb-4">
    <div class="card-body">
        <h2 class="card-title">{{ Str::substr($post->title, 0, 100) }}...</h2>
        <p class="card-text mb-1">{{ Str::substr($post->slug, 0, 250) }}...</p>
        <div class="small text-muted">{{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }}</div>
        <a class="text-primary" href="{{ url('posts/' . $post->slug) }}">Read more →</a>
    </div>
</div>
