<x-layout>
    <x-slot:header>Post Create</x-slot>
        <div class="container">
            <div class="card mt-2">
                <div class="card-header">
                    <h3 class="card-title">Create Post</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('posts.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" value="" class="form-control"
                                placeholder="Enter post's title">
                            @error('title')
                            <div class="invalid-feedback" style="display: block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="category">Category</label>
                            <div class="select2-purple">
                                <select class="select2" name="category[]" multiple="multiple"
                                    data-placeholder="Select a State" data-dropdown-css-class="select2-purple"
                                    style="width: 100%;">
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                <div class="invalid-feedback" style="display: block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="body">Description</label>
                            <textarea id="summernote" name="body"></textarea>
                            @error('body')
                            <div class="invalid-feedback" style="display: block">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{route('posts.index')}}" class="btn btn-secondary">Back</a>
                    </form>
                </div>
            </div>
        </div>
        @push('script')
        <script>
            $(document).ready(function() {
            $("#summernote").summernote(
                {
                    height: 300,
                }
            );
            $('.select2').select2(
                {
                    multiple: true,
                }
            );
        });
        </script>
        @endpush
</x-layout>