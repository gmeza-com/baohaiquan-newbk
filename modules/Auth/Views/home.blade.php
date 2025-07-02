@extends('layout')

@section('layout')

    <!-- Login Container -->
    <div id="login-container">
        <div class="block">
            <!-- Login Title -->
            <div class="block-title">
                <h3>
                    {!! trans('auth::language.message') !!}
                </h3>
            </div>

            <!-- END Login Title -->

            <!-- Login Block -->
            <div class="block-body" style="padding-bottom: 0">
                <!-- Login Form -->
                <form action="{{ url('/auth') }}" method="post" data-callback="redirect_to" id="form-login"
                    class="form-horizontal form-bordered form-control-borderless form-validate">
                    <input type="hidden" name="action" value="login">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="gi gi-user"></i></span>
                                <input type="text" id="login-username" name="username" class="form-control"
                                    placeholder="{{ trans('user::language.username') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="gi gi-asterisk"></i></span>
                                <input type="password" id="login-password" name="password" class="form-control"
                                    placeholder="{{ trans('user::language.password') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-actions">
                        <div class="col-xs-4">
                            <label class="switch switch-primary" data-toggle="tooltip"
                                title="{{ trans('auth::language.remember_me') }}">
                                <input type="checkbox" id="login-remember-me" name="remember" value="1">
                                <span></span>
                            </label>
                        </div>
                        <div class="col-xs-8 text-right">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-angle-right"></i>
                                {{ trans('auth::language.login') }}</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12 text-center">
                            <a href="javascript:void(0)" id="link-reminder-login">
                                {{ trans('auth::language.forgot_password') }}
                            </a>
                        </div>
                    </div>
                </form>
                <!-- END Login Form -->

                <!-- Reminder Form -->
                <form action="{{ url('/auth') }}" method="post" data-callback="redirect_to" id="form-reminder"
                    class="form-horizontal form-bordered form-control-borderless display-none form-validate">
                    <input type="hidden" name="action" value="forgot">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="gi gi-envelope"></i></span>
                                <input type="text" id="reminder-email" name="email" class="form-control input-lg"
                                    placeholder="{{ trans('user::language.email') }}" required data-rule-email="1">
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-actions">
                        <div class="col-xs-12 text-right">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-angle-right"></i>
                                {{ trans('auth::language.send_me_password') }}
                            </button>
                        </div>
