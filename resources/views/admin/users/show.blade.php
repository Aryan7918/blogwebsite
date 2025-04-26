<x-layout>
    <x-slot:header>User Show</x-slot>
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
                        <div class="col-12 ">
                            <label for="firstName" class="col-form-label">First Name</label>
                            <input type="text" id="fname" class="form-control" name="fname"
                                value="{{ $user->fname }}" aria-describedby="firstNameHelpInline">
                        </div>
                    </div>
                    <div class="row g-3 align-items-center mb-3">
                        <div class="col-12">
                            <label for="firstName" class="col-form-label">Last Name</label>
                            <input type="text" id="lname" class="form-control" name="lname"
                                value="{{ $user->lname }}" aria-describedby="lastNameHelpInline">
                        </div>
                    </div>
                    <div class="row g-3 align-items-center mb-3">
                        <div class="col-12">
                            <label for="email" class="col-form-label">Email</label>
                            <input type="text" id="email6" class="form-control" name="email"
                                value="{{ $user->email }}" aria-describedby="emailHelpInline">
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
