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

    <x-slot:header>Posts Table </x-slot>
        <div class="card mx-3 mb-3">
            <div class="card-header">
                <div class=" row ">
                    <div class="d-flex col-md-8">
                        @can('create_post')
                        <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm mr-1 ">Create
                            Post
                        </a>
                        <button type="button" id="btn_trashed" class="mx-1 btn btn-primary btn-sm">
                            <i class="far fa-trash-alt pt-2 px-1"></i>
                        </button>
                        @endcan
                        @can('delete_post')

                        <button type="button" class="btn btn-danger mx-1 mr-2 bulk_button btn-change btn-sm"
                            id="bulk_delete" disabled>
                            Delete
                        </button>
                        <button type="button" class="btn btn-success bulk_button btn-sm" id="bulk_restore" disabled
                            style="display: none">
                            <i class="fas fa-recycle mt-1 mx-1"></i>
                        </button>
                        @endcan
                        <select name="status" id="selectstatus" disabled="disabled" class="mx-1 bulk_button text-center"
                            style="width: 25% !important;">
                            <option></option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <x-alert-message />
                        <div class="alert alert-success alert-dismissible fade hide p-2 m-0" id="msgId" role="alert"
                            style="height: 2rem; display:none">
                            <p class="p-0 m-0 pr-4 pl-2" id="message"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                {{ $dataTable->table() }}

            </div>
        </div>
        @push('script')
        {{ $dataTable->scripts() }}
        <script>
            $(document).ready(function() {
                $('#selectstatus').select2({
                    placeholder: 'Select Status',
                });
            });
        </script>
        <script src="{{asset('asset/js/start.js')}}"></script>
        @endpush
        @push('script')
        <script>
            $(document).ready(function() {
                $('tbody').on('click', '.delete-post-btn', function(e) {
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, Delete this"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            e.preventDefault();
                            var id = $(this).data('id');
                            // $('#deletepostForm').on('submit', function(e) {
                                e.preventDefault();
                                let url = "{{ route('posts.destroy', '/id') }}";
                                url = url.replace('/id', id);
                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                            .attr('content')
                                    },
                                    type: 'DELETE',
                                    url: url,
                                    success: function(response) {
                                        window.LaravelDataTables["data-table"].ajax.reload();
                                        toastr.success(response.message);
                                        $('#selectstatus').select2({
                                            placeholder: 'Select Status',
                                        });

                                    },
                                    error: function(response) {
                                        if (response.status == 403) {
                                            $('#deletePostModal').modal('hide');
                                            window.LaravelDataTables["data-table"]
                                                .ajax.reload();
                                            toastr.error(response.responseJSON.message);
                                            $('#selectstatus').select2({
                                                placeholder: 'Select Status',
                                            });
                                        }
                                    }
                                // });
                            });
                        }
                    });

                });
                $('body').on('change', '.statusClass', function(e) {
                    e.preventDefault();
                    var id = $(this).data('id');
                    var status = $(this).find("option:selected").val();
                    let url = "{{ route('postStatusUpdate') }}";
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
                            if (response.status == 403) {
                                window.LaravelDataTables["data-table"].ajax.reload();
                                toastr.error(response.responseJSON.message);
                            }
                        }
                    });
                });
                $(document).on('click', '#bulk_delete', function(e) {
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
                            $('input[name="post_checkbox"]:checked').each(function() {
                                ids.push($(this).data('id'));
                            });
                            if (ids.length == 0) {
                                $('#message').text('Please select at least one post');
                                $('#msgId').removeClass('hide').addClass('show');
                            } else {
                                let url = "{{ route('posts.bulkdelete') }}";
                                $.ajax({
                                    type: 'POST',
                                    url: url,
                                    data: {
                                        ids: ids
                                    },
                                    success: function(response) {
                                        window.LaravelDataTables["data-table"].ajax.reload();
                                        toastr.success(response.message);
                                        $('#selectstatus').select2({
                                            placeholder: 'Select Status',
                                        });
                                        allCheckBoxClear()
                                   },
                                    error: function(response) {
                                        window.LaravelDataTables["data-table"].ajax
                                            .reload();
                                        toastr.error(response.message);
                                        $('#selectstatus').select2({
                                            placeholder: 'Select Status',
                                        });
                                        allCheckBoxClear()
                                    }
                                });
                            }
                        }
                    });
                });
                $(document).on('change', '#selectstatus', function(e) {
                    e.preventDefault();
                    $('.btn-change').removeAttr('id').html('Update').attr('id','bulk_update_status');
                    var ids = [];
                    $('input[name="post_checkbox"]:checked').each(function() {
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
                                    let url = "{{ route('posts.bulkUpdateStatus') }}";
                                    $.ajax({
                                        type: 'POST',
                                        url: url,
                                        data: {
                                            ids: ids,
                                            status: status
                                        },
                                        success: function(response) {
                                            window.LaravelDataTables["data-table"].ajax.reload();
                                            allCheckBoxClear();
                                            $('.bulk_button').attr('disabled', true);
                                            toastr.success(response.message);
                                            $('#selectstatus').select2({
                                                placeholder: 'Select Status',
                                            });
                                            $('.btn-change').removeAttr('id').attr('id','bulk_delete').html('Delete');
                                            $('#selectstatus').val('').trigger('change');
                                        },
                                        error: function(response) {
                                            window.LaravelDataTables["data-table"].ajax.reload();
                                            toastr.success(response.responseJSON.message);
                                            $('#selectstatus').select2({
                                                placeholder: 'Select Status',
                                            });
                                            $('.btn-change').removeAttr('id').attr('id','bulk_delete').html('Delete');
                                            $('#selectstatus').val('').trigger('change');
                                        }
                                    });
                                }
                            });
                        });
                    }
                });
                singleRestore('posts/restorePost');
                bulkRestore('posts');
            });
        </script>
        @endpush
</x-layout>