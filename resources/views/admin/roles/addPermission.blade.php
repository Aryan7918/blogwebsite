<x-layout>
    <x-slot:header>
        Add Permission to Role
    </x-slot>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Add Permission : {{ $role->name }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ url('roles/addPermission', $role->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="permission">Permission</label>

                                @foreach ($permissions as $permission)
                                    <input type="checkbox" name="permission[]" value="{{$permission->id}}" class="ml-4" >
                                    <label for="permission" class="mr-4">{{$permission->name}}</label>
                                @endforeach
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{route('roles.index')}}" class="btn btn-secondary ml-1">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
