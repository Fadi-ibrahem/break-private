<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta name="description" content="">

    <title>{{ config('app.name') }}</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/css/main-teal.css') }}" media="all">

    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/css/font-awesome.min.css') }}">

    @if (app()->getLocale() == 'ar')
        {{-- google font --}}
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cairo:400,600&display=swap">

        <style>
            body {
                font-family: 'cairo', 'sans-serif';
            }

            .breadcrumb-item+.breadcrumb-item {
                padding-left: .5rem;
            }

            .breadcrumb-item+.breadcrumb-item::before {
                padding-left: .5rem;
            }

            div.dataTables_wrapper div.dataTables_paginate ul.pagination {
                margin: 2px 2px;
            }
        </style>
    @endif

    {{-- jquery --}}
    <script src="{{ asset('admin_assets/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/jquery-ui.js') }}"></script>

    {{-- noty --}}
    <link rel="stylesheet" href="{{ asset('admin_assets/js/plugins/noty/noty.css') }}">
    <script src="{{ asset('admin_assets/js/plugins/noty/noty.min.js') }}"></script>

    {{-- confirm --}}
    <link rel="stylesheet" href="{{ asset('admin_assets/css/jquery-confirm.min.css') }}">

    {{-- <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script> --}}
    {{-- <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script> --}}
    <script src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>

    <link rel="stylesheet" href="{{ asset('admin_assets/css/custom.css') }}">
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    @stack('css')
</head>

<body class="app">

    @include('layouts.admin._header')

    @include('layouts.admin._aside')

    <main class="app-content">

        @include('admin.partials._session')

        @yield('content')

        <div class="modal fade general-modal" id="add-brand" aria-labelledby="add-brand" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>

                </div>
            </div>
        </div>

        <div id="counter" hidden></div>
        <div hidden>
            <form id="stopForm" action="{{ route('breaks.update') }}" method="post">
                @csrf
                @method('put')
            </form>
        </div>

    </main><!-- end of main -->

    {{-- datatable --}}
    <script type="text/javascript" src="{{ asset('admin_assets/js/plugins/jquery.dataTables/jquery.dataTables.min.js') }}">
    </script>
    <script type="text/javascript"
        src="{{ asset('admin_assets/js/plugins/dataTables.bootstrap/dataTables.bootstrap.min.js') }}"></script>


    <!-- Essential javascripts for application to work-->
    <script src="{{ asset('admin_assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/bootstrap.min.js') }}"></script>

    {{-- select 2 --}}
    <script type="text/javascript" src="{{ asset('admin_assets/js/plugins/select2/select2.min.js') }}"></script>

    <script src="{{ asset('admin_assets/js/main.js') }}"></script>

    {{-- ckeditor --}}
    <script src="{{ asset('admin_assets/js/plugins/ckeditor/ckeditor.js') }}"></script>


    {{-- custom --}}
    <script src="{{ asset('admin_assets/js/custom/index.js') }}"></script>
    <script src="{{ asset('admin_assets/js/custom/roles.js') }}"></script>

    {{-- confirm --}}
    <script src="{{ asset('admin_assets/js/jquery-confirm.min.js') }}"></script>

    <script src="{{ asset('admin_assets/js/jquery.countdown.min.js') }}"></script>

    <script>
        $(document).ready(function() {

            //delete
            $(document).on('click', '.delete, #bulk-delete', function(e) {

                var that = $(this)

                e.preventDefault();

                var n = new Noty({
                    text: "@lang('site.confirm_delete')",
                    type: "alert",
                    killer: true,
                    buttons: [
                        Noty.button("@lang('site.yes')", 'btn btn-success mr-2', function() {
                            let url = that.closest('form').attr('action');
                            let data = new FormData(that.closest('form').get(0));

                            let loadingText =
                                '<i class="fa fa-circle-o-notch fa-spin"></i>';
                            let originalText = that.html();
                            that.html(loadingText);

                            n.close();

                            $.ajax({
                                url: url,
                                data: data,
                                method: 'post',
                                processData: false,
                                contentType: false,
                                cache: false,
                                success: function(response) {

                                    $("#record__select-all").prop("checked",
                                        false);

                                    $('.datatable').DataTable().ajax.reload();

                                    new Noty({
                                        layout: 'topRight',
                                        type: 'alert',
                                        text: response,
                                        killer: true,
                                        timeout: 2000,
                                    }).show();

                                    that.html(originalText);
                                },

                            }); //end of ajax call

                        }),

                        Noty.button("@lang('site.no')", 'btn btn-danger mr-2', function() {
                            n.close();
                        })
                    ]
                });

                n.show();

            }); //end of delete

        }); //end of document ready

        CKEDITOR.config.language = "{{ app()->getLocale() }}";
    </script>

    @if (auth()->user()->can('view-break-requests'))
        <script>
            // websocket client logic
            const audio = new Audio('{{ asset('wav/request.wav') }}');

            // Customize Channel name to fit with supervisor and their assistants
            let channelName = `supervisors.{{ Auth::user()->id }}`;
            if (`{{ Auth::user()->type }}` == 'employee' && `{{ Auth::user()->is_assist }}`) {
                channelName = `supervisors.{{ Auth::user()->supervisor_id }}`;
            }

            Echo.private(channelName).listen('NewBreakRequest', (e) => {
                if (window.location.href == '{{ route('admin.breaks.index') }}') {
                    $('#requestsTableBody').prepend(e.html)
                } else {
                    new Noty({
                        layout: 'topRight',
                        type: 'alert',
                        text: "new break request",
                        killer: true,
                        timeout: 2000,
                    }).show();
                }


                $('#requests-count').text(e.pendingRequests)

                audio.play().catch((e) => 0);
            }).listen('BreakRequestHandled', (e) => {
                if (window.location.href == '{{ route('admin.breaks.index') }}') {
                    $(`#requestsTableBody tr#${e.requestId}`).remove()
                }

                $('#requests-count').text(e.pendingRequests)
            });



            // document.addEventListener("DOMContentLoaded", function() {

            // });
        </script>
    @endif

    <script>
        const approvedAudio = new Audio('{{ asset('wav/approved.wav') }}');
        const declinedAudio = new Audio('{{ asset('wav/declined.wav') }}');
        Echo.private(`users.{{ Auth::user()->id }}`)
            .listen('BreakRequestUpdate', (e) => {
                console.log(e);
                if (e.approved) {
                    approvedAudio.play().catch((e) => 0);
                    counter(e.timestamp)
                } else {
                    declinedAudio.play().catch((e) => 0);
                }
                $('#create-request').removeAttr("disabled");
                $(`#${e.id}`).replaceWith(e.html)
            });
    </script>
    @stack('scripts')

</body>

</html>
