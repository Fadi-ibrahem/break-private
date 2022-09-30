@extends('layouts.admin.app')

@section('content')

<div>
    <h2>@lang('site.breaks')</h2>
</div>

<ul class="breadcrumb mt-2">
    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">@lang('site.home')</a></li>
    <li class="breadcrumb-item">@lang('site.breaks')</li>
</ul>

<div class="row">

    <div class="col-md-12">

        <div class="tile">
            <div class="row">
                <!-- Button trigger modal -->
                <button type="button" id="create-request" class="btn btn-primary ml-3" @if(!auth()->user()->can('create-break-request')) disabled @endif data-toggle="modal" data-target="#exampleModalCenter">
                    Request a Break
                </button>

                <!-- Modal -->
                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalCenterTitle">Request a Break</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{route('breaks.store')}}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Select Reason</label>
                                            <select class="form-control" name="reason">
                                                @foreach ($reasons as $reason )
                                                <option value="{{ $reason }}">{{$reason}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-12 mt-2">
                                            <label>Select Time</label>
                                            <select class="form-control" name="time">
                                                @foreach ($times as $time)
                                                <option value="{{ $time }}">{{$time}} minutes</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div><!-- end of row -->

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Request</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End Modal -->

            </div>
        </div><!-- end of tile -->

    </div><!-- end of col -->

</div><!-- end of row -->


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
                                    <th>@lang('site.reason')</th>
                                    <th>@lang('site.is_approved')</th>
                                    <th>@lang('site.requested_time')</th>
                                    <th>@lang('site.actual_time')</th>
                                    <th>@lang('site.start_time')</th>
                                    <th>@lang('site.end_time')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($breaks as $break)
                                @include('site.breaks._break-row')
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

@if (isset($breaks[0]) && $breaks[0]->isActive())
<script type="text/javascript">
    counter('{{$breaks[0]->start_time->timestamp}}')
</script>
@endif

@endpush