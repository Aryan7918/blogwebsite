$(document).ready(function () {
    $(document).on("change", ".checkAllBtn", function (e) {
        var checked = $(this).is(":checked");
        let allchecked = $("#checkAllBtn").is(":checked");
        $(".class_checkbox").prop("checked", allchecked);
        if (checked) {
            $(".bulk_button").removeAttr("disabled");
        } else {
            $(".bulk_button").attr("disabled", true);
            resetSelect2value();
            $('.btn-change').removeAttr('id').attr('id','bulk_delete').html('Delete');
        }
    });
    $(document).on('change', '.class_checkbox', function(e) {
        var checked = $(this).is(':checked');
        let allchecked = $('.class_checkbox').length == $('.class_checkbox:checked').length;
        $('#checkAllBtn').prop('checked', allchecked);
        if (checked) {
            $('.bulk_button').removeAttr('disabled');
        } else if ($('.class_checkbox:checked').length == 0) {
            $('.bulk_button').attr('disabled', true);
            resetSelect2value();
            $('.btn-change').removeAttr('id').attr('id','bulk_delete').html('Delete');
        } else {
            $('.checkAllBtn').prop('checked', false);
        }

    });
    $('#btn_trashed').click(function(e) {
        allCheckBoxClear();
        $(this).toggleClass('Active');
        let has_class = $(this).hasClass('Active');
        if(has_class){
            $('#selectstatus').select2('destroy').hide();
            $('#bulk_restore').css('display','block');
            $(this).children('i').toggleClass('far').toggleClass('fas')
            $("#data-table").on("preXhr.dt", function(e, settings, data) {
                data.status = 'Active';
            });
            $('#data-table').DataTable().ajax.reload();
            $('.btn-add').hide();
        }else{
            $('#bulk_restore').css('display','none');
            $('#selectstatus').select2({
                placeholder: 'Select Status',
            });
            $(this).children('i').toggleClass('far').toggleClass('fas')
            $("#data-table").on("preXhr.dt", function(e, settings, data) {
                data.status = 'Inactive';
            });
            $('#data-table').DataTable().ajax.reload();
            $('.btn-add').show();
        }
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

});

function resetSelect2value(){
    $('#selectstatus').val(null).trigger('change');
}

function allCheckBoxClear(){
    $('.class_checkbox').prop('checked', false);
    $('#checkAllBtn').prop('checked', false);
    $('.bulk_button').attr('disabled', true);

}


function bulkRestore(url){
    $(document).on('click', '#bulk_restore', function(e) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Restore these!"
        }).then((result) => {
            if (result.isConfirmed) {
                e.preventDefault();
                var ids = [];
                let cbs = document.querySelectorAll('.class_checkbox');
                cbs.forEach(element => {
                    if(element.checked){
                        ids.push(element.dataset.id);
                    }
                });
                if (ids.length > 0) {
                    let restoreUrl = 'http://blogsite.test/'+url+'/bulkrestore';
                    $.ajax({
                        type: 'POST',
                        url: restoreUrl,
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
                            window.LaravelDataTables["data-table"].ajax.reload();
                            toastr.error(response.responseJSON.message);
                            $('.bulk_button').attr('disabled', true);
                            allCheckBoxClear()
                        }
                    });
                }
            }
        });
    });
}

function singleRestore(url){
    $(document).on('click', '#restoreTrashed', function(e) {
        Swal.fire({
            title: "Are you sure?",
            text: "You want to restore this.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Restore it!"
        }).then((result) => {
            if (result.isConfirmed) {
                e.preventDefault();
                var id = $(this).data('id');
                let restoreUrl = 'http://blogsite.test/'+url+'';
                console.log(restoreUrl);
                // return;
                $.ajax({
                    type: 'POST',
                    url: url,
                    data:{
                        id:id
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        window.LaravelDataTables["data-table"].ajax.reload();
                    },
                    error: function(response) {
                        window.LaravelDataTables["data-table"].ajax.reload();
                        if (response.status == 403) {
                            toastr.error(response.responseJSON.message);
                        }
                    }
                });
            }
        });
    });
}
