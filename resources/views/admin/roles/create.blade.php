<x-layout>
    <x-slot:header>
        Role Create
    </x-slot>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Create Role</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('roles.store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    {{ $message }}
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Create</button>
                            <a href="{{route('roles.index')}}" class="btn btn-secondary ml-1">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
