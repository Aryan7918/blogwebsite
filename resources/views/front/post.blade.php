<x-front-layout>
    <x-slot:pageTitle>
        {{ $post->title }}
        </x-slot>
        <div class="container mt-5">
            <div class="row">
                <div class="col-lg-8">
                    <article>
                        <header class="mb-4">
                            <h1 class="fw-bolder mb-1">
                                {{ $post->title }}
                            </h1>
                            <div class="text-muted fst-italic mb-2">
                                Posted on {{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }} by
                                {{ $post->user->fname }}
                                {{ $post->user->lname }}
                            </div>
                            @foreach ($post->category->pluck('name') as $name)
                            <a class="badge bg-secondary text-decoration-none link-light"
                                href="/categories/posts/{{ $name }}">{{ $name }}</a>

                            @endforeach
                        </header>
                        <section class="mb-5">
                            <p class="fs-5 mb-4">
                                {!! $post->body !!}
                            </p>
                        </section>
                    </article>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d-flex likeDislike">
                                <i class="far fa-regular fa-thumbs-up p-2 popover like" id="like"
                                    style="font-size: xx-large" data-id="{{$post->id}}" data-like="1">:
                                    {{$likes}}</i>
                                <i class="far fa-regular fa-thumbs-down p-2 popover mx-2 like" id="dislike"
                                    style="font-size: xx-large" data-id="{{$post->id}}" data-like="0">:
                                    {{$dislikes}}</i>
                            </div>
                            <div class="like-error">
                                <p></p>
                            </div>
                        </div>
                    </div>
                    <!-- Comments section-->
                    <section class="mb-5 mt-2">
                        <div class="card bg-light">
                            <div class="card-body">

                                <!-- Comment form-->
                                <form method="post" action="{{ route('comment.store') }}">
                                    @csrf
                                    <textarea class="form-control" rows="3" name="content" id="content"
                                        placeholder="Join the discussion and leave a comment!" {{ Auth::check() ? ''
                                        : 'disabled' }}>
                                    </textarea>
                                    @if (Auth::check())

                                    <input type="hidden" name="post_id" id="post_id" value="{{ $post->id }}">
                                    <button class="btn btn-outline-secondary my-2" type="submit">Post comment</button>
                                    @else
                                    <div class="mt-2">
                                        <a href="{{ route('login') }}">Go to login page, Click here</a>
                                    </div>
                                    @endif
                                </form>
                                @if($post->comment)

                                @foreach ($post->comment as $comment )

                                @if ($comment->parent_id==null)
                                <div class="d-flex mb-4">

                                    <!-- Parent comment-->
                                    <div class="flex-shrink-0">
                                        <img class="rounded-circle" src="https://dummyimage.com/50x50/ced4da/6c757d.jpg"
                                            alt="..." />
                                    </div>
                                    <div class="ms-3">
                                        <div class="fw-bold">
                                            {{ $comment->user->fname }} {{ $comment->user->lname }}
                                        </div>
                                        <p class="m-0 p-0">
                                            {{ $comment->content }}
                                        </p>
                                        @if (Auth::check())
                                        <div class="d-flex">
                                            @if ($comment->user_id == Auth::user()->id)
                                            <button type="submit" class="btn btn-sm btn-danger deleteComment"
                                                data-id="{{ $comment->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary editComment"
                                                data-id="{{ $comment->id }}">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                            @endif
                                            <button class="reply btn btn-sm btn-secondary">
                                                <i class="fas fa-reply"></i></button>
                                        </div>
                                        <div class="formComment" style="display:none">
                                            @include('front.comment-form')
                                        </div>
                                        @endif
                                        @if ($comment->replies->isNotEmpty())

                                        <!-- Child comment -->
                                        @foreach ($comment->replies as $replies)
                                        <div class="d-flex mt-4">
                                            <div class="flex-shrink-0">
                                                <img class="rounded-circle"
                                                    src="https://dummyimage.com/50x50/ced4da/6c757d.jpg" alt="..." />
                                            </div>
                                            <div class="ms-3">
                                                <div class="fw-bold">
                                                    {{ $replies->user->fname }} {{ $replies->user->lname }}
                                                </div>
                                                <p class="m-0 p-0">
                                                    {{ $replies->content }}
                                                </p>
                                                @if (Auth::check())
                                                @if ($replies->user_id == Auth::user()->id)
                                                <div class="d-flex">
                                                    <form>
                                                        <button type="submit"
                                                            class="btn btn-danger btn-sm deleteComment"
                                                            data-id="{{ $replies->id }}">
                                                            <i class="fas fa-trash"></i></button>
                                                    </form>
                                                    <button class="btn btn-primary btn-sm editComment"
                                                        data-id="{{ $replies->id }}">
                                                        <i class="fas fa-pencil-alt"></i></button>
                                                </div>
                                                @endif
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                        @endif
                                    </div>

                                </div>
                                @endif
                                @endforeach
                                @endif

                            </div>
                    </section>
                </div>
                <!-- Side widgets-->
                <div class="col-lg-4">
                    <!-- Search widget-->
                    <x-front-searchbar />
                    <!-- Categories widget-->
                    <x-category-box :categories="$categories" />
                </div>
            </div>
        </div>
        @push('scripts')
        <script>
            $(document).ready(function() {
                $('.reply').on('click', function() {
                    $(this).parent().next('.formComment').toggle();
                });
                $("body").on('click',".deleteComment", function(e) {
                    e.preventDefault();
                    var id = $(this).data('id');
                    let url = "{{ route('comment.destroy', '/id') }}";
                    url = url.replace('/id', id);
                    $.ajax({
                        type: 'DELETE',
                        url: url,
                        success: function(response) {
                            console.log(response);
                            if (response.status == 200) {
                                $('#commentCreate')[0].reset();
                            }
                            location.reload();
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                });
                $("body").on('click',".editComment", function(e) {
                    e.preventDefault();
                    var cmt =$(this).parent('div').prev('p');
                    var prevComment = cmt.html().trim();
                    cmt.html('<textarea class="form-control" rows="3" name="content" id="content"></textarea>');
                    cmt.children('textarea').val(prevComment);
                    $(this).html('Update').addClass('updateComment').removeClass('editComment');
                    console.log("object");

                });
                $("body").on('click', '.updateComment',function(e) {
                    e.preventDefault();
                    var id = $(this).data('id');
                    var content = $(this).parent('div').prev('p').children('textarea').val();
                    let url = "{{ route('comment.update', '/id') }}";
                    url = url.replace('/id', id);
                    $(this).parent('div').prev('p').addClass("updatedClass");
                    $.ajax({
                        type: 'PUT',
                        url: url,
                        data: {
                            content: content,
                            id: id,
                        },
                        success: function(response){
                            $('.updatedClass').html(response.content);
                            $('.updatedClass').next('div').children('.updateComment').addClass('editComment').removeClass('updateComment').html('<i class="fas fa-pencil-alt"></i>');
                            $('.updatedClass').removeClass('updatedClass');
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                });
                $("body").on('click','.like',function(e){
                    e.preventDefault();
                    var post_id = $(this).data('id');
                    var is_like = $(this).data('like');
                    console.log(is_like);
                    let url = "{{ route('likePost')}}";
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            post_id: post_id,
                            is_like: is_like,
                        },
                        success: function(response){
                            showLike()
                            console.log(response);
                            $('#like').html(": "+response.likes);
                            $('#dislike').html(": "+response.dislikes);

                        },
                        error: function(response) {
                            if(response.status==401){
                                $(".likeDislike").next().html("<p class='text-sm mb-0 text-danger'>Please login!</p>");
                            }
                        }
                    });
                });
                showLike()
                function showLike()
                {
                    let user_id = {{ auth()->id() }};
                    let post_id = {{ $post->id }};
                    let url = "{{route('liked')}}";
                    $.ajax({
                        type: 'post',
                        url: url,
                        data:{
                            user_id:user_id,
                            post_id:post_id,
                        },
                        success: function(response){
                            if(response.is_like==1){
                                $('#like').addClass('bg-success');
                                $('#dislike').removeClass('bg-danger');
                            }else if(response.is_like==0){
                                $('#dislike').addClass('bg-danger');
                                $('#like').removeClass('bg-success');
                            }else{
                                $('#like').removeClass('bg-success');
                                $('#dislike').removeClass('bg-danger');
                            }
                        },
                        error: function(response) {
                            if(response.status==401){
                            }
                        }
                    });
                }
            });
        </script>
        @endpush
</x-front-layout>