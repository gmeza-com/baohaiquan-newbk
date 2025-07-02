<header class="navbar navbar-default">
    <!-- Left Header Navigation -->
    <button class="btn-link" style="margin: 5px 0 5px -9px" onclick="App.sidebar('toggle-sidebar');this.blur();">
        <i class="fa fa-bars fa-fw"></i>
    </button>

    <!-- END Left Header Navigation -->

    <!-- Right Header Navigation -->
    <ul class="nav navbar-nav-custom pull-right">
        @if (!$user->is_super_admin && false)
            <!-- Notify Dropdown -->
            <li class="dropdown">
                <button class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-comments-o"></i>
                </button>
                <ul class="dropdown-menu dropdown-custom dropdown-menu-right">
                    <li class="dropdown-header text-center">{{ trans('user::language.notification') }}</li>
                    <li>
                        <div class="alert alert-success alert-alt">
                            <small>5 min ago</small><br>
                            <i class="fa fa-thumbs-up fa-fw"></i> You had a new sale ($10)
                        </div>
                        <div class="alert alert-info alert-alt">
                            <small>10 min ago</small><br>
                            <i class="fa fa-arrow-up fa-fw"></i> Upgraded to Pro plan
                        </div>
                        <div class="alert alert-warning alert-alt">
                            <small>3 hours ago</small><br>
                            <i class="fa fa-exclamation fa-fw"></i> Running low on space<br><strong>18GB in use</strong>
                            2GB left
                        </div>
                        <div class="alert alert-danger alert-alt">
                            <small>Yesterday</small><br>
                            <i class="fa fa-bug fa-fw"></i> <a href="javascript:void(0)" class="alert-link">New bug
                                submitted</a>
                        </div>
                    </li>
                </ul>
            </li>
            <!-- END Notify Dropdown -->
        @endif

        <!-- User Dropdown -->
        <li class="dropdown">
            <button class="dropdown-toggle btn-link" data-toggle="dropdown">
                <img src="{{ $user->avatar }}" alt="avatar">
            </button>
            <ul class="dropdown-menu dropdown-custom dropdown-menu-right">
                <li class="dropdown-header text-center">
                    @if (!$user->is_super_admin)
                        {{ $user->username }}
                    @else
                        {{ trans('user::language.account') }}
                    @endif
                </li>
                @if (!$user->is_super_admin && false)
                    <li>
                        <a href="page_ready_user_profile.html">
                            <i class="fa fa-user fa-fw pull-right"></i>
                            {{ trans('user::language.profile') }}
                        </a>
                    </li>
                    <li class="divider"></li>
                @endif
                <li>
                    <a href="{{ route('auth.logout') }}">
                        <i class="fa fa-ban fa-fw pull-right"></i>
                        {{ trans('user::language.logout') }}
                    </a>
                </li>
            </ul>
        </li>
        <!-- END User Dropdown -->
    </ul>
    <!-- END Right Header Navigation -->
</header>
<!-- END Header -->
