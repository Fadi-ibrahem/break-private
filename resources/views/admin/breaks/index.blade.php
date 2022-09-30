@extends('layouts.admin.app')

@section('content')

<div>
    <h2>@lang('site.break_requests')</h2>
</div>

<ul class="breadcrumb mt-2">
    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">@lang('site.home')</a></li>
    <li class="breadcrumb-item">@lang('site.break_requests')</li>
</ul>


<div class="row">
    <div class="col-md-12">
        <div class="tile shadow">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered no-footer" role="grid" aria-describedby="sampleTable_info" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>@lang('site.date')</th>
                                    <th>@lang('site.requested_by')</th>
                                    <th>@lang('users.code')</th>
                                    <th>@lang('site.reason')</th>
                                    <th>@lang('site.requested_time')</th>
                                    <th>@lang('site.today_requests')</th>
                                    <th>@lang('site.action')</th>
                                </tr>
                            </thead>
                            <tbody id="requestsTableBody">
                                @foreach ($pendingRequests as $request )
                                @include('admin.breaks._request-row')
                                @endforeach
                            </tbody>
                        </table>
                    </div><!-- end of table responsive -->
                </div><!-- end of col -->
            </div><!-- end of row -->
        </div><!-- end of tile -->
    </div><!-- end of col -->
</div><!-- end of row -->
<hr>
<div class="mb-3">
    <h2>@lang('site.current_breaks')</h2>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="tile shadow">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered no-footer" id="currentBreaks" role="grid" aria-describedby="sampleTable_info" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>@lang('site.date')</th>
                                    <th>@lang('users.name')</th>
                                    <th>@lang('users.code')</th>
                                    <th>@lang('site.reason')</th>
                                    <th>@lang('site.start_time')</th>
                                    <th>@lang('site.requested_time')</th>
                                    <th>@lang('site.actual_time')</th>
                                </tr>
                            </thead>
                            <tbody id="requestsTableBody">
                                @foreach ($activeRequests as $break )
                                @include('admin.breaks._current-break-row')
                                @endforeach
                            </tbody>
                        </table>
                    </div><!-- end of table responsive -->
                </div><!-- end of col -->
            </div><!-- end of row -->
        </div><!-- end of tile -->
    </div><!-- end of col -->
</div><!-- end of row -->
@endsection

@push('scripts')
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        function initCounter() {
            var $this = $(this);
            const timestamp = $(this).data('timestamp');
            let totalSeconds = (+new Date() / 1000) - timestamp;
            $this.html(formatTotalSeconds(totalSeconds));
            setInterval(() => {
                totalSeconds++
                $this.html(formatTotalSeconds(totalSeconds));
            }, 1000)
        };

        $('[data-timestamp]').each(initCounter);

        $('body').delegate('.approve, .decline', 'click', function(e) {
            e.preventDefault()
            $(this).attr('disabled', 'disabled').find('i').removeClass('fa-thumbs-up').addClass('fa-spinner fa-pulse')

            $(this).closest('form').next().find('.decline').attr('disabled', 'disabled')
            const form = $(this).closest('form')
            const url = form.attr('action');
            const data = new FormData(form.get(0));
            $.ajax({
                url: url,
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                method: 'post',
                processData: false,
                contentType: false,
                cache: false,
            }); //end of ajax call
        })

        let channelName = `supervisors.{{Auth::user()->id}}`

        if(`{{Auth::user()->type}}` == 'employee' && `{{Auth::user()->is_assist}}`) {
            
            channelName = `supervisors.{{Auth::user()->supervisor_id}}`
        } 


         Echo.private(channelName).listen('BreakEnded', (e) => {
            if (e.id) {
                $(`#b-${e.id}`).remove();
            }
        }).listen('BreakRequestCurrent', (e) => {
            if (window.location.href == '{{route("admin.breaks.index")}}') {
                if (e.is_approved) {
                        $('#currentBreaks').prepend(e.html)
                        $(`tr#b-${e.id} [data-timestamp]`).each(initCounter);
                    }
                }
        });
        
    });
</script>

@endpush