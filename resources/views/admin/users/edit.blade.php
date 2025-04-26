<x-layout>
    <x-slot:header>User Edit</x-slot>
    <div class="container">
        <div class="card mt-2">
            <div class="card-header text-center">
                <h1>Edit User</h1>
            </div>
            <div class="card-body">
                <form action="{{ route('users.update', [$user->id]) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="row g-3 align-items-center mb-3">
                        <div class="col-6 ">
                            <label for="firstName" class="col-form-label">First Name</label>
                            <input type="text" id="fname" class="form-control" name="fname"
                                value="{{ $user->fname }}" aria-describedby="firstNameHelpInline">
                            @error('fname')
                                <p class="text-danger text-xs mt-2 mb-0">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label for="lastName" class="col-form-label">Last Name</label>
                            <input type="text" id="lname" class="form-control" name="lname"
                                value="{{ $user->lname }}" aria-describedby="lastNameHelpInline">
                            @error('lname')
                                <p class="text-danger text-xs mt-2 mb-0">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3 align-items-center mb-3">
                        <div class="col-6">
                            <label for="email" class="col-form-label">Email</label>
                            <input type="text" id="email6" class="form-control" name="email"
                                value="{{ $user->email }}" aria-describedby="emailHelpInline">
                            @error('email')
                                <p class="text-danger text-xs mt-2 mb-0">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label for="birthdate" class="col-form-label">Birthdate</label>
                            <input type="date" id="birthdate6" class="form-control" name="birthdate"
                                value="{{ $user->birthdate }}" aria-describedby="birthdateHelpInline">
                            @error('birthdate')
                                <p class="text-danger text-xs mt-2 mb-0">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3 align-items-center mb-3">
                        <div class="col-6">
                            <label for="mobile" class="col-form-label">Mobile</label>
                            <input type="integer" id="email6" class="form-control" name="mobile"
                                value="{{ $user->mobile }}" aria-describedby="emailHelpInline">
                                @error('mobile')
                                <p class="text-danger text-xs mt-2 mb-0">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label for="status" class="col-form-label">Status</label>
                            <select class="custom-select" name="status">
                                <option value="{{ $user->status}}">{{ $user->status}}</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                              </select>
                              @error('status')
                                <p class="text-danger text-xs mt-2 mb-0">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3 align-items-center mb-3">
                        <div class="col-6">
                            <label for="role" class="col-form-label">Password</label>
                            <input type="password" name="password" id="password6" class="form-control"
                            value="{{ $user->password }}" >
                        </div>
                        <div class="col-6">
                            <label for="status" class="col-form-label">Role</label>
                            <select class="custom-select" name="user_role">
                                <option value="{{ $user->user_role}}" selected>{{ $user->user_role}}</option>
                                <option value="admin">Admin</option>
                                <option value="author">Author</option>
                                <option value="reader">Reader</option>
                              </select>
                              @error('user_role')
                                <p class="text-danger text-xs mt-2 mb-0">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary form-control">Update</button>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('users.index') }}" type="button"
                                class="btn btn-secondary form-control">Back</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

</x-layout>
