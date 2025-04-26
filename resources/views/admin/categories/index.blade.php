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
    <x-slot:header>Categories Table</x-slot>

        <div class="card mx-3 mb-3">
            <div class="card-header">
                <div class="row mb-0 mt-1 d-flex justify">
                    @can('create_category')
                    <button type="button" class="btn btn-primary mr-1 addCatBtn btn-sm" data-toggle="modal"
                        data-target="#addcategory">
                        Add Category
                    </button>
                    <button type="button" id="btn_trashed" class="mx-1 btn btn-primary btn-sm">
                        <i class="far fa-trash-alt pt-2 px-1"></i>
                    </button>
                    @endcan
                    @can('delete_category')
                    <button type="button" class="btn btn-danger mx-1 mr-2 bulk_button btn-sm btn-change btn-change-2"
                        id="bulk_delete" disabled>
                        Delete
                    </button>
                    <button type="button" class="btn btn-success bulk_button btn-sm" id="bulk_restore" disabled
                        style="display: none">
                        <i class="fas fa-recycle mt-1 mx-1"></i>
                    </button>
                    @endcan
                    @can('edit_category')

                    <select name="status" id="selectstatus" disabled="disabled" class="bulk_button text-center"
                        style="width: 16% !important">
                        <option></option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    @endcan
                    <div class="alert alert-success alert-dismissible fade hide p-2 m-0 right-1" id="msgId"
                        role="alert">
                        <p class="p-0 m-0 pr-4 pl-2" id="message"></p>
                        <button type="button" class="close p-2 pr-2" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>

        {{-- Add Category --}}
        <div class="modal fade" id="addcategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="addCategoryForm">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="categoryName">Category Name</label>
                                <input type="text" class="form-control" id="AddCatName" name="name"
                                    aria-describedby="nameHelp" placeholder="Enter Category Name">
                                <span class="err-class text-sm text-danger" id="err-name"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-primary" id="submitCategory" name="submit">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- Edit Category --}}
        <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="editCategoryForm">
                        <input type="hidden" id="Editcatid" name="id" value="">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="categoryName">Category Name</label>
                                <input type="text" class="form-control" id="EditcatName" name="name"
                                    aria-describedby="nameHelp" value="">
                                <span id="error-name" class="error-class text-sm text-danger"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="updateCategory">Update</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
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
        <script>
            $(document).ready(function() {
                $('.addCatBtn').click(function(e) {
                    e.preventDefault();
                    $('#AddCatName').val('');
                });
                resetSelect2value();

                $('#addCategoryForm').on('submit', function(e) {
                    e.preventDefault();
                    var name = $('#AddCatName').val();
                    // console.log(name);
                    $.ajax({
                        url: "{{ route('categories.store') }}",
                        type: 'POST',
                        data: {
                            name: name
                        },
                        success: function(data) {
                            window.LaravelDataTables["data-table"].ajax.reload();
                            $('#addcategory').modal('hide');
                            toastr.success(data.message);
                        },
                        error: function(response) {
                            let error = response.responseJSON.errors;
                            if (response.status == 422) {
                                $('#AddCatName').next(".err-class").html(" ");
                                $.each(error, function(key, value) {
                                    $('#AddCatName').next('#err-' + key.replace('.', ''))
                                        .html(value);
                                });
                            }
                        }

                    });
                });
                $('tbody').on('click', '.edit-category-btn', function(e) {
                    e.preventDefault();
                    var id = $(this).data('id');
                    $.ajax({
                        type: 'GET',
                        url: '/categories/' + id,
                        success: function(data) {
                            $('#Editcatid').val(data.id);
                            $('#EditcatName').val(data.name);
                            $('#editCategoryModal').modal('show');
                        },
                        error: function(response) {
                            toastr.error('No found');
                        }

                    });
                });
                $('#editCategoryForm').on('submit', function(e) {
                    e.preventDefault();
                    var id = $('#Editcatid').val();
                    var name = $('#EditcatName').val();
                    let url = "{{ route('categories.update', '/id') }}";
                    url = url.replace('/id', id);
                    // console.log(url);
                    $.ajax({
                        url: url,
                        type: 'PUT',
                        data: {
                            id: id,
                            name: name
                        },
                        success: function(response1) {
                            window.LaravelDataTables["data-table"].ajax.reload();
                            $('#editCategoryModal').modal('hide');
                            toastr.success(response1.message);
                        },
                        error: function(response) {
                            let error = response.responseJSON.errors;
                            if (response.status == 422) {
                                $('#EditCatName').next(".error-class").html(" ");
                                $.each(error, function(key, value) {
                                    // $('#EditCatName').next().html(value);
                                    $('#error-' + key.replace('.', '')).html(value);
                                });
                            }
                            if (response.status == 403) {
                                // console.log(response.responseJSON.message);
                                toastr.success(response.responseJSON.message);
                                window.LaravelDataTables["data-table"].ajax.reload();
                                $('#editCategoryModal').modal('hide');
                            }
                        }
                    });
                });
                $(document).on('click', '.delete-category-btn', function(e) {
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
                            let url = "{{ route('categories.destroy', '/id') }}";
                            url = url.replace('/id', id);
                            $.ajax({
                                type: 'DELETE',
                                url: url,
                                success: function(response) {
                                    // console.log(response);
                                    window.LaravelDataTables["data-table"].ajax
                                        .reload();
                                    $('#deleteCategoryModal').modal('hide');
                                    toastr.success(response.message);
                                },
                                error: function(response) {
                                    //console.log(response.error);
                                    if (response.status == 403) {
                                        $('#deleteCategoryModal').modal('hide');
                                        window.LaravelDataTables["data-table"].ajax
                                            .reload();
                                        toastr.error(response.responseJSON.message);
                                    }
                                }
                            });
                        };
                    });
                });
                $('body').on('change', '.statusClass', function(e) {
                    e.preventDefault();
                    var id = $(this).data('id');
                    var status = $(this).find("option:selected").val();
                    let url = "{{ route('categoryStatusUpdate') }}";
                    // url = url.replace('/id', id);

                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            id: id,
                            status: status
                        },
                        success: function(response) {
                            // console.log(response.message);
                            window.LaravelDataTables["data-table"].ajax
                                .reload();
                            toastr.success(response.message);
                        },
                        error: function(response) {
                            if (response.status == 403) {
                                // console.log(response.responseJSON.message);
                                window.LaravelDataTables["data-table"].ajax
                                    .reload();
                                toastr.success(response.responseJSON.message);
                            }
                        }
                    });
                });
                $('#selectstatus').change(function (e) {
                    // e.preventDefault();
                    $('.btn-change').removeAttr('id').html('Update').attr('id','bulk_update_status');
                    var ids = [];
                    $('input[name="category_checkbox"]:checked').each(function() {
                        ids.push($(this).data('id'));
                    });
                    //console.log(ids);
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
                                    let url = "{{ route('categories.bulkUpdateStatus') }}"
                                    $.ajax({
                                        type: 'POST',
                                        url: url,
                                        data: {
                                            ids: ids,
                                            status: status
                                        },
                                        success: function(response) {
                                            $('.btn-change').removeAttr('id').attr('id','bulk_delete').html('Delete');
                                            window.LaravelDataTables["data-table"].ajax.reload();
                                            $('#checkAllBtn').prop('checked',false);
                                            $('.bulk_button').attr('disabled',true);
                                            toastr.success(response.message);
                                        },
                                        error: function(response) {
                                            // console.log(response);
                                            window.LaravelDataTables["data-table"].ajax.reload();
                                            toastr.success(response.message);
                                            $('.btn-change').removeAttr('id').attr('id','bulk_delete').html('Delete');

                                        }
                                    });
                                }
                            });
                        });
                    }
                });
                $(document).on('click', '#bulk_delete', function(e) {
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete these!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            e.preventDefault();
                            var ids = [];
                            $('input[name="category_checkbox"]:checked').each(function() {
                                ids.push($(this).data('id'));
                            });
                            if (ids.length > 0) {
                                let url = "{{ route('categories.bulkdelete') }}";
                                $.ajax({
                                    type: 'POST',
                                    url: url,
                                    data: {
                                        ids: ids
                                    },
                                    success: function(response) {
                                        window.LaravelDataTables["data-table"].ajax
                                            .reload();
                                        toastr.success(response.message);
                                        $('.bulk_button').attr('disabled', true);
                                    },
                                    error: function(response) {
                                        window.LaravelDataTables["data-table"].ajax
                                            .reload();
                                        toastr.error(response.message);
                                        $('.bulk_button').attr('disabled', true);
                                    }
                                });
                            }
                        }
                    });
                });
                // $(document).on('click', '#restoreTrashed', function(e) {
                //     Swal.fire({
                //         title: "Are you sure?",
                //         text: "You want to restore this.",
                //         icon: "warning",
                //         showCancelButton: true,
                //         confirmButtonColor: "#3085d6",
                //         cancelButtonColor: "#d33",
                //         confirmButtonText: "Yes, Restore it!"
                //     }).then((result) => {
                //         if (result.isConfirmed) {
                //             e.preventDefault();
                //             var id = $(this).data('id');
                //             //console.log(id);
                //             let url = "{{ route('restoreCategory', '/id') }}";
                //             url = url.replace('/id', id);
                //             //console.log(url);
                //             $.ajax({
                //                 type: 'POST',
                //                 url: url,
                //                 success: function(response) {
                //                     // //console.log(response);
                //                     toastr.success(response.message);
                //                     window.LaravelDataTables["data-table"].ajax.reload();
                //                 },
                //                 error: function(response) {
                //                     //console.log(response);
                //                     window.LaravelDataTables["data-table"].ajax.reload();
                //                     if (response.status == 403) {
                //                         toastr.error(response.responseJSON.message);
                //                     }
                //                 }
                //             });
                //         }
                //     });
                // });
                // $(document).on('click', '#bulk_restore', function(e) {
                //     Swal.fire({
                //         title: "Are you sure?",
                //         text: "You won't be able to revert this!",
                //         icon: "warning",
                //         showCancelButton: true,
                //         confirmButtonColor: "#3085d6",
                //         cancelButtonColor: "#d33",
                //         confirmButtonText: "Yes, Restore these!"
                //     }).then((result) => {
                //         if (result.isConfirmed) {
                //             e.preventDefault();
                //             var ids = [];
                //             // $('input[name="category_checkbox"]:checked').each(function() {
                //             //     ids.push($(this).data('id'));
                //             // });
                //             let cbs = document.querySelectorAll('.class_checkbox');
                //             cbs.forEach(element => {
                //                 if(element.checked){
                //                     ids.push(element.dataset.id);
                //                 }
                //             });
                //             // return;

                //             // //console.log(ids);
                //             if (ids.length > 0) {
                //                 let url = "{{ route('categories.bulkrestore') }}";
                //                 $.ajax({
                //                     type: 'POST',
                //                     url: url,
                //                     data: {
                //                         ids: ids
                //                     },
                //                     success: function(response) {
                //                         //console.log(response.message);
                //                         window.LaravelDataTables["data-table"].ajax.reload();
                //                         toastr.success(response.message);
                //                         $('#selectstatus').val('').trigger('change');
                //                         $('.bulk_button').attr('disabled', true);
                //                         allCheckBoxClear()
                //                     },
                //                     error: function(response) {
                //                         //console.log(response);
                //                         window.LaravelDataTables["data-table"].ajax.reload();
                //                         toastr.success(response.message);
                //                         $('#selectstatus').val('').trigger('change');
                //                         $('.bulk_button').attr('disabled', true);
                //                         allCheckBoxClear()
                //                     }
                //                 });
                //             }
                //         }
                //     });
                // });
                singleRestore('categories/restoreCategory');
                bulkRestore('categories');
            });
        </script>
        @endpush

</x-layout>