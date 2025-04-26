<x-layout>
    <x-slot:header>Post Edit</x-slot>
        <div class="container">
            <div class="card mt-2">
                <div class="card-header text-center">
                    <h1>Edit Post</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('posts.update', [$post->id]) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" value="{{ $post->title }}" class="form-control">
                            @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Categories</label>
                            <div class="select2-purple">
                                <select class="select2" name="category[]" multiple="multiple"
                                    data-placeholder="Select Category" data-dropdown-css-class="select2-purple"
                                    style="width: 100%;">
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{$post->
                                        category->contains('id',$category->id)?'selected':''}}>{{
                                        Str::title($category->name) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" class="form-control singleSelect">
                                <option value="{{ $post->status }}" selected>{{ $post->status }}</option>
                                @if ($post->status=="Active")
                                <option value="Inactive">Inactive</option>
                                @else
                                <option value="Active">Active</option>
                                @endif
                            </select>
                            @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="body">Description</label>
                            <textarea id="summernote" name="body">
                            {{$post->body}}
                        </textarea>
                            @error('body')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{route('posts.index')}}" class="btn btn-secondary ml-1">Back</a>
                    </form>
                </div>
            </div>
        </div>
        @push('script')
        <script>
            $(document).ready(function () {
                $('#summernote').summernote({
                    height: 300,
                });
                $('.select2').select2(
                {
                    multiple: true,
                });
                $('.singleSelect').select2({
                    height:100,
                });
            });
        </script>
        @endpush
</x-layout>