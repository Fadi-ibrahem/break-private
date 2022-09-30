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

            <form method="post" action="{{ route('admin.users.assign.manager') }}">
                @csrf
                @method('post')

                @include('admin.partials._errors')

                <div class="form-group">
                    <legend>Choose Manager<span class="text-danger">*</span></legend>
                    <select name="manager_id" id="s2" class="form-control">
                        <option selected disabled>---</option>
                        @foreach($managers as $manager)
                        <option value="{{$manager->id}}">{{$manager->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                <fieldset>
                    <legend>Select Supervisor To A Specific Manager<span class="text-danger">*</span></legend>
                    <select name="supervisors[]" multiple="multiple" class="form-control js-example-basic-multiple">
                        @foreach($supervisors as $supervisor)
                        <option value="{{$supervisor->id}}">{{$supervisor->name}} &lpar; {{$supervisor->code}} &rpar;</option>
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