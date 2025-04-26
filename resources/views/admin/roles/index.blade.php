<x-layout>
    <x-slot:header>
        Roles
        </x-slot>
        <div id="accordion">
            @foreach ($roles as $role)
            <div class="card mx-3">
                <div class="card-header" id="headingOne">
                    <h5 class="mb-0 d-flex justify-content-between">
                        <button class="btn btn-link text-lg" data-toggle="collapse" data-target="#{{ $role->name }}"
                            aria-expanded="true" aria-controls="collapseOne">
                            {{ Str::ucfirst($role->name) }}
                        </button>
                        <p class="mt-3 text-sm">No. Members: {{$role->users->count()}}</p>
                    </h5>
                </div>
                <div id="{{ $role->name }}" class="collapse hide" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                        @foreach ($permissionGroups as $module => $permissions)
                        <div class="row">
                            <div class="col-2">
                                <h5>{{ Str::ucfirst($module) }}</h5>
                            </div>
                            <div class="col-10">
                                @foreach ($permissions as $perm)
                                {{-- <div class="form-check d-flex"> --}}
                                    <input type="checkbox" name="{{ $perm->name }}" data-id="{{ $role->id }}"
                                        id="{{ $perm->name}}" value="{{ $perm->name }}" {{
                                        $role->hasPermissionTo($perm->name) ? 'checked' : null
                                    }}
                                    class="checkboxSwitch mr-1 ml-3" />
                                    <label for="{{ $perm->name}}">
                                        {{ Str::ucfirst(Str::replace('_', ' ', $perm->name)) }}
                                    </label>
                                    {{--
                                </div> --}}
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>


        @push('script')
        <script>
            $(document).ready(function() {
                $('body').on('change', '.checkboxSwitch', function(e) {
                    let checked = $(this).is(':checked');
                    let permission = $(this).val();
                    let id = $(this).data('id');
                    let url = "{{ route('givePermission') }}";
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        url: url,
                        data: {
                            id:id,
                            name: name,
                            checked: checked,
                            permission: permission
                        },
                        success: function(response) {
                            // console.log(response);
                            toastr.success(response.message);
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                });

            });
        </script>
        @endpush
</x-layout>