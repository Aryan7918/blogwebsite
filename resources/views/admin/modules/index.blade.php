<x-layout>
    <x-slot:header>Module Table</x-slot>
        <div class="card mx-3 mb-3">
            <div class="card-header">
                <div class="row mb-0 mt-1 d-flex justify-content-between">
                    <x-alert-message />
                    <div class="alert alert-success alert-dismissible fade hide p-2 m-0" id="msgId" role="alert"
                        style="height: 2.5rem; display:none">
                        <p class="p-0 m-0 pr-4 pl-2" id="message"></p>
                    </div>
                </div>
            </div>
            <div class="card-body ">
                {{ $dataTable->table() }}
            </div>
        </div>
        @push('styles')
        <style>
            /* From Uiverse.io by Galahhad */
            .switch {
                /* switch */
                --switch-width: 46px;
                --switch-height: 24px;
                --switch-bg: rgb(131, 131, 131);
                --switch-checked-bg: rgb(0, 218, 80);
                --switch-offset: calc((var(--switch-height) - var(--circle-diameter)) / 2);
                --switch-transition: all .2s cubic-bezier(0.27, 0.2, 0.25, 1.51);
                /* circle */
                --circle-diameter: 18px;
                --circle-bg: #fff;
                --circle-shadow: 1px 1px 2px rgba(146, 146, 146, 0.45);
                --circle-checked-shadow: -1px 1px 2px rgba(163, 163, 163, 0.45);
                --circle-transition: var(--switch-transition);
                /* icon */
                --icon-transition: all .2s cubic-bezier(0.27, 0.2, 0.25, 1.51);
                --icon-cross-color: var(--switch-bg);
                --icon-cross-size: 6px;
                --icon-checkmark-color: var(--switch-checked-bg);
                --icon-checkmark-size: 10px;
                /* effect line */
                --effect-width: calc(var(--circle-diameter) / 2);
                --effect-height: calc(var(--effect-width) / 2 - 1px);
                --effect-bg: var(--circle-bg);
                --effect-border-radius: 1px;
                --effect-transition: all .2s ease-in-out;
            }

            .switch input {
                display: none;
            }

            .switch {
                display: inline-block;
            }

            .switch svg {
                -webkit-transition: var(--icon-transition);
                -o-transition: var(--icon-transition);
                transition: var(--icon-transition);
                position: absolute;
                height: auto;
            }

            .switch .checkmark {
                width: var(--icon-checkmark-size);
                color: var(--icon-checkmark-color);
                -webkit-transform: scale(0);
                -ms-transform: scale(0);
                transform: scale(0);
            }

            .switch .cross {
                width: var(--icon-cross-size);
                color: var(--icon-cross-color);
            }

            .slider {
                -webkit-box-sizing: border-box;
                box-sizing: border-box;
                width: var(--switch-width);
                height: var(--switch-height);
                background: var(--switch-bg);
                border-radius: 999px;
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-align: center;
                -ms-flex-align: center;
                align-items: center;
                position: relative;
                -webkit-transition: var(--switch-transition);
                -o-transition: var(--switch-transition);
                transition: var(--switch-transition);
                cursor: pointer;
            }

            .circle {
                width: var(--circle-diameter);
                height: var(--circle-diameter);
                background: var(--circle-bg);
                border-radius: inherit;
                -webkit-box-shadow: var(--circle-shadow);
                box-shadow: var(--circle-shadow);
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-align: center;
                -ms-flex-align: center;
                align-items: center;
                -webkit-box-pack: center;
                -ms-flex-pack: center;
                justify-content: center;
                -webkit-transition: var(--circle-transition);
                -o-transition: var(--circle-transition);
                transition: var(--circle-transition);
                z-index: 1;
                position: absolute;
                left: var(--switch-offset);
            }

            .slider::before {
                content: "";
                position: absolute;
                width: var(--effect-width);
                height: var(--effect-height);
                left: calc(var(--switch-offset) + (var(--effect-width) / 2));
                background: var(--effect-bg);
                border-radius: var(--effect-border-radius);
                -webkit-transition: var(--effect-transition);
                -o-transition: var(--effect-transition);
                transition: var(--effect-transition);
            }

            /* actions */

            .switch input:checked+.slider {
                background: var(--switch-checked-bg);
            }

            .switch input:checked+.slider .checkmark {
                -webkit-transform: scale(1);
                -ms-transform: scale(1);
                transform: scale(1);
            }

            .switch input:checked+.slider .cross {
                -webkit-transform: scale(0);
                -ms-transform: scale(0);
                transform: scale(0);
            }

            .switch input:checked+.slider::before {
                left: calc(100% - var(--effect-width) - (var(--effect-width) / 2) - var(--switch-offset));
            }

            .switch input:checked+.slider .circle {
                left: calc(100% - var(--circle-diameter) - var(--switch-offset));
                -webkit-box-shadow: var(--circle-checked-shadow);
                box-shadow: var(--circle-checked-shadow);
            }
        </style>
        @endpush
        @push('script')
        {{ $dataTable->scripts() }}
        @endpush
        @push('script')
        <script>
            $(document).ready(function () {
                $('body').on('change','.checkboxSwitch',function(e){
                    var checked = $(this).is(':checked');
                    var permission = $(this).data('name');
                    var id= $(this).data('id');
                    let url="{{route('givePermissionToUser')}}";
                    console.log(url);
                    console.log(permission);
                    $.ajax({
                        headers:{
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]'). attr('content')
                            },
                        type: 'POST',
                        url: url,
                        data:{
                            id:id,
                            checked:checked,
                            permission:permission
                        },
                        success: function(response){
                            console.log(response);
                            toastr.success(response.message)
                        },
                        error: function(error){
                            console.log(error);
                        }
                    });
                });
                $('body').on('change','.roleClass',function(e){
                    e.preventDefault();
                    var id=$(this).data('id');
                    // console.log(this);
                    var role = $(this).find("option:selected").val();
                    // console.log(role);
                    let url="{{route('assignRoleToUser')}}";
                    console.log(id);
                    $.ajax({
                        headers:{
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]'). attr('content')
                            },
                            type: 'POST',
                            url: url,
                            data: {
                                id:id,
                                role:role
                            },
                            success: function(response){
                                window.LaravelDataTables["module-table"].ajax.reload();
                                $('#message').text(response.message);
                                $('#hasSuccessMsg').removeClass('show');
                                $('#msgId').removeClass('hide').addClass('show');
                                $('#msgId').css('display','block')
                                setTimeout(function(){
                                    $('#msgId').removeClass('show').addClass('hide');
                                    $('#msgId').css('display','none')
                                }, 3000);
                            },
                            error: function(response){
                                console.log(response);
                                if(response.status==403){
                                    console.log(response.responseJSON.message);
                                    window.LaravelDataTables["module-table"].ajax.reload();
                                    $('#message').text(response.responseJSON.message);
                                    $('#hasSuccessMsg').removeClass('show');
                                    $('#msgId').removeClass('hide').addClass('show');
                                    $('#msgId').css('display','block')
                                    setTimeout(function(){
                                        $('#msgId').removeClass('show').addClass('hide');
                                        $('#msgId').css('display','none')
                                    }, 3000);
                                }
                            }
                    });
                })

            });

        </script>
        @endpush

</x-layout>