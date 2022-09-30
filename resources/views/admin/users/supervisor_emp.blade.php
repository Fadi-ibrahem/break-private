@extends('layouts.admin.app')

@section('content')

<div>
    <h2>@lang('users.users')</h2>
</div>

<ul class="breadcrumb mt-2">
    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">@lang('site.home')</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">@lang('users.users')</a></li>
    <li class="breadcrumb-item">@lang('site.assign')</li>
</ul>

<div class="row">

    <div class="col-md-12">

        <div class="tile shadow">

            <form method="post" action="{{ route('admin.users.assign') }}">
                @csrf
                @method('post')

                @include('admin.partials._errors')

                <div class="form-group">
                    <legend>Choose Supervisor<span class="text-danger">*</span></legend>
                    <select name="supervisor_id" id="s2" class="form-control">
                        @foreach($supervisors as $supervisor)
                        <option value="{{$supervisor->id}}">{{$supervisor->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                <fieldset>
                    <legend>Select Employees To A Specific Supervisor<span class="text-danger">*</span></legend>
                    <select name="employees[]" multiple="multiple" class="form-control js-example-basic-multiple">
                        @foreach($employees as $employee)
                        <option value="{{$employee->id}}">{{$employee->name}} &lpar; {{$employee->code}} &rpar;</option>
                        @endforeach
                    </select>
                </fieldset>

                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i>@lang('site.assign')</button>
                </div>

            </form><!-- end of form -->

        </div><!-- end of tile -->

    </div><!-- end of col -->

</div><!-- end of row -->

@endsection