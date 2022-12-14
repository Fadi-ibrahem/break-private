@extends('layouts.admin.app')

@section('content')

<div>
    <h2>@lang('users.users')</h2>
</div>

<ul class="breadcrumb mt-2">
    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">@lang('site.home')</a></li>
    <li class="breadcrumb-item">@lang('users.users')</li>
</ul>

<div class="row">

    <div class="col-md-12">

        <div class="tile shadow">

            <div class="row mb-2">

                <div class="col-md-12">

                    {{-- Create New User Link --}}
                    @if (auth()->user()->hasPermission('create_users'))
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.create')</a>

                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                        Import from excell
                    </button>

                    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalCenterTitle">Import Employees</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group>">
                                            <input type="file" name="employees" class="form-control" title="file must be excell">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                    <!-- End Import From Excel Modal -->

                    {{-- Bulk Delete --}}
                    @if (auth()->user()->hasPermission('delete_users'))
                    <form method="post" action="{{ route('admin.users.bulk_delete') }}" style="display: inline-block;">
                        @csrf
                        @method('delete')
                        <input type="hidden" name="record_ids" id="record-ids">
                        <button type="submit" class="btn btn-danger" id="bulk-delete" disabled="true"><i class="fa fa-trash"></i> @lang('site.bulk_delete')</button>
                    </form><!-- end of form -->
                    @endif

                    {{-- Start Assign New Supervisors & Managers --}}
                    @if(auth()->user()->type == 'super_admin')
                        <a href="{{ route('admin.users.showAssign') }}" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.assign_emp_to_supervisor')</a>
                        <a href="{{ route('admin.users.showAssignManager') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Assign Manager to Supervisor</a>
                    @endif
                    {{-- End Assign New Supervisors & Managers --}}

                </div>

            </div><!-- end of row -->

            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" id="data-table-search" class="form-control" autofocus placeholder="@lang('site.search')">
                    </div>
                </div>

            </div><!-- end of row -->

            <div class="row">

                <div class="col-md-12">

                    <div class="table-responsive">

                        <table class="table datatable" id="users-table" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="animated-checkbox">
                                            <label class="m-0">
                                                <input type="checkbox" id="record__select-all">
                                                <span class="label-text"></span>
                                            </label>
                                        </div>
                                    </th>
                                    <th>@lang('users.name')</th>
                                    <th>@lang('users.email')</th>
                                    <th>@lang('users.code')</th>
                                    <th>@lang('users.extension')</th>
                                    <th>@lang('users.type')</th>
                                    @if (auth()->user()->type == 'manager' || auth()->user()->type == 'super_admin')
                                        <th>His/Her Supervisor</th>
                                    @endif
                                    @if (auth()->user()->type == 'super_admin')
                                        <th>His/Her Manager</th>
                                    @endif
                                    <th>@lang('site.created_at')</th>
                                    <th>@lang('site.action')</th>
                                </tr>
                            </thead>
                        </table>

                    </div><!-- end of table responsive -->

                </div><!-- end of col -->

            </div><!-- end of row -->

        </div><!-- end of tile -->

    </div><!-- end of col -->

</div><!-- end of row -->

@endsection

@push('scripts')

<script>
    @if(auth()->user()->type == 'super_admin')
    let usersTable = $('#users-table').DataTable({
        dom: "tiplr",
        serverSide: true,
        processing: true,
        "language": {
            "url": "{{ asset('admin_assets/datatable-lang/' . app()->getLocale() . '.json') }}"
        },
        ajax: {
            url: "{{ route('admin.users.data') }}",
        },
        columns: [{
                data: 'record_select',
                name: 'record_select',
                searchable: false,
                sortable: false,
                width: '1%'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'email',
                name: 'email'
            },
            {
                data: 'code',
                name: 'code'
            },
            {
                data: 'extension',
                name: 'extension'
            },
            {
                data: 'type',
                name: 'type'
            },
            {
                data: 'related_supervisor',
                name: 'related_supervisor'
            },
            {
                data: 'related_manager',
                name: 'related_manager'
            },
            {
                data: 'created_at',
                name: 'created_at',
                searchable: false
            },
            {
                data: 'actions',
                name: 'actions',
                searchable: false,
                sortable: false,
                width: '20%'
            },
        ],
        order: [
            [2, 'desc']
        ],
        drawCallback: function(settings) {
            $('.record__select').prop('checked', false);
            $('#record__select-all').prop('checked', false);
            $('#record-ids').val();
            $('#bulk-delete').attr('disabled', true);
        }
    });
    @endif()

    @if(auth()->user()->type == 'manager')
    let usersTable = $('#users-table').DataTable({
        dom: "tiplr",
        serverSide: true,
        processing: true,
        "language": {
            "url": "{{ asset('admin_assets/datatable-lang/' . app()->getLocale() . '.json') }}"
        },
        ajax: {
            url: "{{ route('admin.users.data') }}",
        },
        columns: [{
                data: 'record_select',
                name: 'record_select',
                searchable: false,
                sortable: false,
                width: '1%'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'email',
                name: 'email'
            },
            {
                data: 'code',
                name: 'code'
            },
            {
                data: 'extension',
                name: 'extension'
            },
            {
                data: 'type',
                name: 'type'
            },
            {
                data: 'related_supervisor',
                name: 'related_supervisor'
            },
            {
                data: 'created_at',
                name: 'created_at',
                searchable: false
            },
            {
                data: 'actions',
                name: 'actions',
                searchable: false,
                sortable: false,
                width: '20%'
            },
        ],
        order: [
            [2, 'desc']
        ],
        drawCallback: function(settings) {
            $('.record__select').prop('checked', false);
            $('#record__select-all').prop('checked', false);
            $('#record-ids').val();
            $('#bulk-delete').attr('disabled', true);
        }
    });
    @endif()

    @if(auth()->user()->type == 'supervisor')
    let usersTable = $('#users-table').DataTable({
        dom: "tiplr",
        serverSide: true,
        processing: true,
        "language": {
            "url": "{{ asset('admin_assets/datatable-lang/' . app()->getLocale() . '.json') }}"
        },
        ajax: {
            url: "{{ route('admin.users.data') }}",
        },
        columns: [{
                data: 'record_select',
                name: 'record_select',
                searchable: false,
                sortable: false,
                width: '1%'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'email',
                name: 'email'
            },
            {
                data: 'code',
                name: 'code'
            },
            {
                data: 'extension',
                name: 'extension'
            },
            {
                data: 'type',
                name: 'type'
            },
            {
                data: 'created_at',
                name: 'created_at',
                searchable: false
            },
            {
                data: 'actions',
                name: 'actions',
                searchable: false,
                sortable: false,
                width: '20%'
            },
        ],
        order: [
            [2, 'desc']
        ],
        drawCallback: function(settings) {
            $('.record__select').prop('checked', false);
            $('#record__select-all').prop('checked', false);
            $('#record-ids').val();
            $('#bulk-delete').attr('disabled', true);
        }
    });
    @endif()

    $('#data-table-search').keyup(function() {
        usersTable.search(this.value).draw();
    })
</script>

@endpush