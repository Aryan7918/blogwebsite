<x-layout>
    <x-slot:header>
        Permissions
    </x-slot>
    <div class="card mx-3 mb-3">
        <div class="card-header">
            <div class="row mb-0 mt-1 d-flex justify-content-between">
                @can('create post')
                <a href="{{ route('permissions.create') }}" style="height: 2.5rem">
                    <button class="btn btn-primary">Create Permission</button>
                </a>
                @endcan
                <x-alert-message/>
                <div class="alert alert-success alert-dismissible fade hide p-2 m-0" id="msgId" role="alert" style="height: 2.5rem; display:none">
                    <p class="p-0 m-0 pr-4 pl-2" id="message"></p>
                </div>
            </div>
          </div>
        <div class="card-body ">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permissions as $permission)
                    <tr>
                        <td>{{ $permission->id }}</td>
                        <td>{{ $permission->name }}</td>
                        <td>
                            <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-info">Edit</a>
                            <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST"
                                    style="display: inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layout>
