<x-front-layout>
    <x-slot:pageTitle>
        Home
        </x-slot>
        <!-- Page header with logo and tagline-->
        <header class="py-5 bg-light border-bottom mb-4">
            <div class="container">
                <div class="text-center my-5">
                    <h1 class="fw-bolder">Welcome to Blog Home!</h1>
                    <p class="lead mb-0">A Bootstrap 5 starter layout for your next blog homepage</p>
                </div>
            </div>
        </header>
        <!-- Page content-->
        <div class="container">
            <div class="row">
                <!-- Blog entries-->
                <div class="col-lg-8">
                    <!-- Featured blog post-->
                    @if($posts->count()>0)
                    <x-featured-post :post="$posts[0]" />
                    <div class="row">
                        @if($posts->count()>1)
                        @foreach ($posts->skip(1) as $post)
                        <x-post-card :post="$post" />
                        @endforeach
                        @endif
                    </div>
                    @else
                    <p>No posts found</p>
                    @endif
                </div>
                <div class="col-lg-4">
                    <!-- Search widget-->
                    <x-front-searchbar />
                    <!-- Categories widget-->
                    <x-category-box :categories="$categories" />
                </div>
            </div>
        </div>
</x-front-layout>