<x-layout>
    @push('styles')
    <style>
        .select2-selection__rendered {
            line-height: 31px !important;
        }

        .select2-container .select2-selection--single {
            height: 35px !important;
        }

        .select2-selection__arrow {
            height: 34px !important;
        }
    </style>
    @endpush
    <x-slot:header>Users Table</x-slot>
        <div class="card mx-3 mb-3">
            <div class="card-header">
                <div class="row row mb-0 mt-1 d-flex justify align-middle">
                    @can('create_user')
                    <a href="{{ route('users.create') }}" class="mx-1 btn btn-primary btn-sm btn-add">
                        Add User
                    </a>
                    <button type="button" id="btn_trashed" class="mx-1 btn btn-primary btn-sm">
                        <i class="far fa-trash-alt pt-2 px-1"></i>
                    </button>
                    @endcan
                    @can('delete_user')
                    <button type="button" class="btn btn-danger mx-1 mr-2 bulk_button btn-change btn-sm"
                        id="bulk_delete" disabled>
                        Delete
                    </button>
                    <button type="button" class="btn btn-success bulk_button btn-sm" id="bulk_restore" disabled
                        style="display: none">
                        <i class="fas fa-recycle mt-1"></i>
                    </button>
                    @endcan
                    @can('edit_user')
                    <select name="status" id="selectstatus" disabled="disabled" class="bulk_button text-center "
                        style="width: 15% !important; height:25% !important;">
                        <option></option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    @endcan
                    <x-alert-message />
                </div>
            </div>
            <div class="card-body ">
                {{ $dataTable->table() }}
            </div>
        </div>
        @push('script')
        {{ $dataTable->scripts() }}
        @endpush

        @push('script')
        <script src="{{ asset('asset/js/start.js') }}"></script>
        <script>
            $(document).ready(function() {
                resetSelect2value();
                $('#selectstatus').select2({
                    placeholder: 'Select Status',
                });
                $('body').on('change', '.statusClass', function(e) {
                    var id = $(this).data('id');
                    var status = $(this).find("option:selected").val();
                    let url = "{{ route('userStatusUpdate') }}";
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            id: id,
                            status: status
                        },
                        success: function(response) {
                            window.LaravelDataTables["data-table"].ajax.reload();
                            toastr.success(response.message);

                        },
                        error: function(response) {
                            console.log(response.responseJSON);
                            if (response.status == 403) {
                                window.LaravelDataTables["data-table"].ajax.reload();
                                toastr.error(response.responseJSON.message);
                            }
                        }
                    });
                });
                $('body').on('change', '.roleClass', function(e) {
                    var id = $(this).data('id');
                    var role = $(this).find("option:selected").val();
                    let url = "{{ route('assignRoleToUser') }}";
                    // url = url.replace('/id', id);
                    // console.log(id);
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            id: id,
                            role: role
                        },
                        success: function(response) {
                            window.LaravelDataTables["data-table"].ajax.reload();
                            toastr.success(response.message);

                        },
                        error: function(response) {
                            if (response.status == 403) {
                                console.log(response.responseJSON.message);
                                window.LaravelDataTables["data-table"].ajax.reload();
                                toastr.error(response.responseJSON.message);
                            }
                        }
                    });
                })
                $('body').on('click', '#bulk_delete', function(e) {
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            e.preventDefault();
                            var ids = [];
                            $('input[name="user_checkbox"]:checked').each(function() {
                                ids.push($(this).data('id'));
                            });
                            // console.log(ids);
                            if (ids.length > 0) {
                                let url = "{{ route('users.bulkdelete') }}";
                                $.ajax({
                                    type: 'POST',
                                    url: url,
                                    data: {
                                        ids: ids
                                    },
                                    success: function(response) {
                                        window.LaravelDataTables["data-table"].ajax.reload();
                                        toastr.success(response.message);
                                        $('.bulk_button').attr('disabled', true);
                                        allCheckBoxClear()
                                    },
                                    error: function(response) {
                                        // console.log(response.message);
                                        window.LaravelDataTables["data-table"].ajax
                                            .reload();
                                        toastr.success(response.message);
                                        $('.bulk_button').attr('disabled', true);
                                        allCheckBoxClear()

                                    }
                                });
                            }
                        }
                    });
                });
                $('body').on('change', '#selectstatus', function(e) {
                    $('.btn-change').removeAttr('id').html('Update').attr('id', 'bulk_update_status');
                    var ids = [];
                    $('input[name="user_checkbox"]:checked').each(function() {
                        ids.push($(this).data('id'));
                    });
                    if (ids.length > 0) {
                        $('body').on('click', '#bulk_update_status', function(e) {
                            Swal.fire({
                                title: "Are you sure?",
                                text: "You won't be able to revert this!",
                                icon: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#3085d6",
                                cancelButtonColor: "#d33",
                                confirmButtonText: "Yes, Update these"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    var status = $('#selectstatus').val();
                                    let url = "{{ route('users.bulkUpdateStatus') }}"
                                    $.ajax({
                                        type: 'POST',
                                        url: url,
                                        data: {
                                            ids: ids,
                                            status: status
                                        },
                                        success: function(response) {
                                            // console.log(response.message);
                                            $('#selectstatus').val('').trigger('change');
                                            $('.btn-change').removeAttr('id').attr('id','bulk_delete').html('Delete');
                                            window.LaravelDataTables["data-table"].ajax.reload();
                                            $('.bulk_button').attr('disabled',true);
                                            toastr.success(response.message);
                                            allCheckBoxClear()
                                        },
                                        error: function(response) {
                                            // console.log(response);
                                            window.LaravelDataTables["data-table"].ajax.reload();
                                            toastr.success(response.message);
                                            resetSelect2value()
                                            $('.btn-change').removeAttr('id').attr('id','bulk_delete').html('Delete');
                                            allCheckBoxClear()
                                        }
                                    });
                                }
                            });
                        });
                    }
                });
                $(document).on('click', '.delete-user-btn', function(e) {
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            e.preventDefault();
                            var id = $(this).data('id');
                            console.log(this);
                            let url = "{{ route('users.destroy', '/id') }}";
                            url = url.replace('/id', id);
                            $.ajax({
                                type: 'DELETE',
                                url: url,
                                success: function(response) {
                                    window.LaravelDataTables["data-table"].ajax.reload();
                                    toastr.success(response.message);
                                },
                                error: function(response) {
                                    if (response.status == 403) {
                                        window.LaravelDataTables["data-table"].ajax.reload();
                                        toastr.error(response.error);

                                    }
                                }
                            });
                        };
                    });
                });

                singleRestore('users/restoreUser');
                bulkRestore('users');
            });
        </script>
        @endpush

</x-layout>