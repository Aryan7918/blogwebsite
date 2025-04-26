<x-layout>
    <x-slot:header>User Create</x-slot>
    <div class="container">
        <div class="card mt-2">
            <div class="card-header text-center">
                <h1>Create User</h1>
            </div>
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="post">
                    @csrf
                    <div class="row g-3 align-items-center mb-1">
                        <div class="col-6 ">
                            <label for="firstName" class="col-form-label">First Name</label>
                            <input type="text" id="fname" class="form-control" name="fname"
                                value="{{ old('fname') }}" aria-describedby="firstNameHelpInline">
                            @error('fname')
                                <p class="text-danger text-xs mt-2 mb-0">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label for="firstName" class="col-form-label">Last Name</label>
                            <input type="text" id="lname" class="form-control" name="lname"
                                value="{{ old('lname') }}" aria-describedby="lastNameHelpInline">
                            @error('lname')
                                <p class="text-danger text-xs mt-2 mb-0">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3 align-items-center mb-1">
                        <div class="col-6">
                            <label for="email" class="col-form-label">Email</label>
                            <input type="email" id="email6" class="form-control" name="email"
                                value="{{ old('email') }}" aria-describedby="emailHelpInline">
                            @error('email')
                                <p class="text-danger text-xs mt-2 mb-0">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label for="birthdate" class="col-form-label">Birth Date</label>
                            <input type="date" id="birthdate6" class="form-control" name="birthdate"
                                value="{{ old('birthdate') }}" aria-describedby="birthdateHelpInline">
                            @error('birthdate')
                                <p class="text-danger text-xs mt-2 mb-0">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3 align-items-center mb-1">
                        <div class="col-6">
                            <label for="mobile" class="col-form-label">Mobile</label>
                            <input type="integer" id="integer6" class="form-control" name="mobile"
                                aria-describedby="mobileHelpInline">
                            @error('mobile')
                                <p class="text-danger text-xs mt-2 mb-0">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label for="password" class="col-form-label">Password</label>
                            <input type="password" id="email6" class="form-control" name="password"
                                aria-describedby="emailHelpInline">
                            @error('password')
                                <p class="text-danger text-xs mt-2 mb-0">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary form-control">Create</button>
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
