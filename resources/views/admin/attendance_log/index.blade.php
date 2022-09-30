 @extends('layouts.admin.app')

 @section('content')

 <div>
     <h2>@lang('site.attendance_log')</h2>
 </div>

 <ul class="breadcrumb mt-2">
     <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">@lang('site.home')</a></li>
     <li class="breadcrumb-item">@lang('site.attendance_log')</li>
 </ul>

 <div class="row">

     <div class="col-md-12">

         <div class="tile">
             <div class="row mb-3">
                 <!-- Button trigger modal -->
                 <button type="button" class="btn btn-primary ml-3" data-toggle="modal" data-target="#exampleModalCenter">
                     Export To Excell
                 </button>

             </div>

             <!-- Modal -->
             <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                 <div class="modal-dialog modal-dialog-centered" role="document">
                     <div class="modal-content">
                         <div class="modal-header">
                             <h5 class="modal-title" id="exampleModalCenterTitle">Export Employees</h5>
                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                 <span aria-hidden="true">&times;</span>
                             </button>
                         </div>
                         <form action="{{ route('admin.attendances.export') }}" method="get">
                             @csrf
                             <div class="modal-body">
                                 <div class="row">
                                     <div class="col-md-12">
                                         <div class="form-group">
                                             <label> From Date </label>
                                             <input class="form-control" name="from" type="date" value="{{request()->query('from')}}" placeholder="Select Date">
                                         </div>
                                     </div>

                                     <div class="col-md-12">
                                         <div class="form-group">
                                             <label> To Date </label>
                                             <input class="form-control" name="to" type="date" value="{{request()->query('to')}}" placeholder="Select Date">
                                         </div>
                                     </div>

                                     <div class="col-md-12">
                                         <div class="form-group">
                                             <div class="form-group">
                                                 <label>Select Employees </label>
                                                 <select class="form-control" id="s1" name="emp_code">
                                                     <option value="">All Employees</option>
                                                     @foreach ($employees as $emp )
                                                     <option value="{{ $emp->code }}" {{ $emp->code == request()->query('emp_code') ? 'selected' : ''}}> {{ $emp->name }} ({{ $emp->code }})</option>
                                                     @endforeach
                                                 </select>
                                             </div>
                                         </div>
                                     </div>
                                 </div><!-- end of row -->

                             </div>
                             <div class="modal-footer">
                                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                 <button type="submit" class="btn btn-primary">Export </button>
                             </div>
                         </form>
                     </div>
                 </div>
             </div>
             <!-- End Modal -->

             {{-- filter form  --}}
             <form action="{{ route('admin.attendances.index') }}" method="GET">
                 <div class="row">
                     <div class="col-md-3">
                         <div class="form-group">
                             <label> From Date </label>
                             <input class="form-control" name="from" type="date" value="{{request()->query('from')}}" placeholder="Select Date">
                         </div>
                     </div>

                     <div class="col-md-3">
                         <div class="form-group">
                             <label> To Date </label>
                             <input class="form-control" name="to" type="date" value="{{request()->query('to')}}" placeholder="Select Date">
                         </div>
                     </div>

                     <div class="col-md-4">
                         <div class="form-group">
                             <div class="form-group">
                                 <label>Select Employees </label>
                                 <select class="form-control" id="s2" name="emp_code">
                                     <option value="" selected>All Employees</option>
                                     @foreach ($employees as $emp )
                                     <option value="{{ $emp->code }}" {{ $emp->code == request()->query('emp_code') ? 'selected' : ''}}> {{ $emp->name }} ({{ $emp->code }})</option>
                                     @endforeach
                                 </select>
                             </div>
                         </div>
                     </div>

                     <div class="col-md-2">
                         <div class="form-group" style="margin-top: 30px">
                             <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> </button>
                         </div>
                     </div>

                 </div><!-- end of row -->
             </form>

         </div><!-- end of tile -->

     </div><!-- end of col -->

 </div><!-- end of row -->


 <div class="row">
     <div class="col-md-12">
         <div class="tile shadow">
             <div class="row">
                 <div class="col-md-12">
                     <div class="table-responsive">
                         <table class="table table-hover table-bordered dataTable no-footer" id="logsTable" role="grid" aria-describedby="sampleTable_info" style="width: 100%;">
                             <thead>
                                 <tr>
                                     <th>@lang('site.date')</th>
                                     <th>@lang('users.name')</th>
                                     <th>@lang('users.code')</th>
                                     <th>@lang('site.check_in')</th>
                                     <th>@lang('site.check_out')</th>
                                     <th>@lang('site.shift')</th>
                                 </tr>
                             </thead>
                             <tbody>
                                 @foreach ($logs as $log )
                                 <tr>
                                     <th>{{ $log->check_in_at->toDateString() }}</th>
                                     <th>{{ $log->name }}</th>
                                     <th>{{ $log->code }}</th>
                                     <th>{{ $log->check_in_at->format('g:i A') }}</th>
                                     <th>{{ $log->check_out_at ? $log->check_out_at->format('g:i A') : '' }}</th>
                                     <th>{{ ( ($log->total_shift_minutes / 60) - ( ($log->total_shift_minutes % 60) / 60 ) ) }} h : {{ $log->total_shift_minutes % 60 }} m</th>
                                 </tr>
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
     $("#s1").select2({
         dropdownParent: $("#exampleModalCenter"),
         'width': '100%'
     });
     $("#s2").select2({
         'width': '100%'
     });
     $('#logsTable').DataTable({
         "language": {
             "url": "{{ asset('admin_assets/datatable-lang/' . app()->getLocale() . '.json') }}"
         },

     });
 </script>

 @endpush