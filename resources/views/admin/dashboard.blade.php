<x-layout>
    <x-slot:header>Dashboard</x-slot>

        <div class="row m-2">
            <x-small-box number="{{ $user }}" text="Total User" class="bag" color="info"
                href="{{route('users.index')}}" />
            <x-small-box number="{{ $category }}" text="Total Category" class="stats-bars" color="warning"
                href="{{route('categories.index')}}" />
            <x-small-box number="{{ $post }}" text="Total Post" class="person-add" color="danger"
                href="{{route('posts.index')}}" />
            <x-small-box number="150" text="New Orders" class="bag" color="success" href="#" />
        </div>
</x-layout>