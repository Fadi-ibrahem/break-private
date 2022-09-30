<!-- Sidebar menu-->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>

<aside class="app-sidebar">
    <div class="app-sidebar__user">
        <img class="app-sidebar__user-avatar" src="{{ auth()->user()->image_path }}" alt="User Image">
        <div>
            <p class="app-sidebar__user-name" style="white-space: break-spaces;">{{ auth()->user()->name }}</p>
            {{-- <p class="app-sidebar__user-designation">{{ auth()->user()->roles->first()->name }}</p> --}}
        </div>
    </div>

    <ul class="app-menu">

        @if (auth()->user()->type == 'super_admin' || auth()->user()->type == 'supervisor' || auth()->user()->type == 'manager')
        <li><a class="app-menu__item {{ request()->is('*home*') ? 'active' : '' }}" href="{{ route('admin.home') }}"><i class="app-menu__icon fa fa-home"></i> <span class="app-menu__label">@lang('site.home')</span></a></li>
        @endif


        {{--roles--}}
        {{-- @if (auth()->user()->hasPermission('read_roles'))
            <li><a class="app-menu__item {{ request()->is('*roles*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}"><i class="app-menu__icon fa fa-lock"></i> <span class="app-menu__label">@lang('roles.roles')</span></a></li>
        @endif --}}

        @if (auth()->user()->type == 'super_admin' || auth()->user()->type == 'supervisor' || auth()->user()->type == 'manager')
        <li><a class="app-menu__item {{ request()->is('*attendances*') ? 'active' : '' }}" href="{{ route('admin.attendances.index') }}"><i class="app-menu__icon fa fa-clock-o"></i> <span class="app-menu__label">@lang('site.attendance_log')</span></a></li>
        @endif

        @if (auth()->user()->type == 'supervisor' || auth()->user()->is_assist || auth()->user()->type == 'manager')
        <li><a class="app-menu__item {{ request()->is('*admin/breaks') ? 'active' : '' }}" href="{{ route('admin.breaks.index') }}"><i class="app-menu__icon fa fa-coffee"></i> <span class="app-menu__label">@lang('site.break_requests')</span>
                <span id="requests-count" class="badge badge-secondary mr-3">{{App\Models\BreakModel::getPendingRequestsCountWithoutAssistant((auth()->user()->type == 'supervisor') ? auth()->id() : auth()->user()->supervisor_id)}}</span></a></li>

        @endif


        @if (auth()->user()->type == 'super_admin' || auth()->user()->type == 'supervisor' || auth()->user()->type == 'manager')
            <li><a class="app-menu__item {{ request()->is('*admin/breaks/reports*') ? 'active' : '' }}" href="{{ route('admin.breaks.reports') }}"><i class="app-menu__icon fa fa-file"></i> <span class="app-menu__label">@lang('site.break_reports')</span></a></li>
        @endif

        @if (auth()->user()->can('view-breaks'))
        <li><a class="app-menu__item {{ request()->is('*breaks') ? 'active' : '' }}" href="{{ route('breaks.index') }}"><i class="app-menu__icon fa fa-coffee"></i> <span class="app-menu__label">@lang('site.breaks')</span></a></li>
        @endif

        {{--admins--}}
        {{-- @if (auth()->user()->hasPermission('read_admins'))
            <li><a class="app-menu__item {{ request()->is('*admins*') ? 'active' : '' }}" href="{{ route('admin.admins.index') }}"><i class="app-menu__icon fa fa-users"></i> <span class="app-menu__label">Employees</span></a></li>
        @endif --}}

        {{--users--}}
        @if (auth()->user()->type == 'super_admin' || auth()->user()->type == 'supervisor' || auth()->user()->type == 'manager')
        <li><a class="app-menu__item {{ request()->is('*users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}"><i class="app-menu__icon fa fa-user"></i> <span class="app-menu__label">@lang('users.users')</span></a></li>
        @endif

        {{--settings--}}
        {{-- @if (auth()->user()->hasPermission('read_settings'))
            <li class="treeview {{ request()->is('*settings*') ? 'is-expanded' : '' }}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-cogs"></i><span class="app-menu__label">@lang('settings.settings')</span><i class="treeview-indicator fa fa-angle-right"></i></a>

        <ul class="treeview-menu">
            <li><a class="treeview-item" href="{{ route('admin.settings.general') }}"><i class="icon fa fa-circle-o"></i>@lang('settings.general')</a></li>
        </ul>
        </li>
        @endif --}}

        {{--profile--}}


        <li class="treeview {{ request()->is('*profile*') || request()->is('*password*')  ? 'is-expanded' : '' }}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-user-circle"></i><span class="app-menu__label">@lang('users.profile')</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li><a class="treeview-item" href="{{ route('profile.edit') }}"><i class="icon fa fa-circle-o"></i>@lang('users.edit_profile')</a></li>
                <li><a class="treeview-item" href="{{ route('profile.password.edit') }}"><i class="icon fa fa-circle-o"></i>@lang('users.change_password')</a></li>
            </ul>
        </li>

    </ul>
</aside>