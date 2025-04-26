<form id="commentCreate" class="mt-2" method="post" action="{{route('comment.store')}}">
    @csrf
    <textarea class="form-control" rows="2" name="content" id="content-form"
        placeholder="Join the discussion and leave a comment!" {{Auth::check()?'':'disabled'}}>
    </textarea>
    <input type="hidden" name="post_id" id="post_id-form" value="{{$post->id}}">
    <input type="hidden" name="parent_id" id="parent_id-form" value="{{$comment->id}}">
    @if (Auth::check())
    <button class="btn btn-outline-secondary my-2 btn-sm" type="submit">Post comment</button>
    @else
    <div class="mt-2">
        <a href="{{route('login')}}">Go to login page, Click here</a>
    </div>
    @endif
</form>