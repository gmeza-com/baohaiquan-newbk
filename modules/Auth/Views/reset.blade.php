@extends('layout')

@section('layout')

    <img src="{{ asset('backend/img/placeholders/backgrounds/login_full_bg.jpg') }}" alt="" class="full-bg animation-pulseSlow">

    <!-- Login Container -->
    <div id="login-container" class="animation-fadeIn">
        <!-- Login Title -->
        <div class="login-title text-center">
            <h1>
                <small>{!! trans('auth::language.message') !!}</small>
            </h1>
        </div>
        <!-- END Login Title -->

        <!-- Login Block -->
        <div class="block push-bit">
            <form action="{{ url('auth') }}"
                  method="post"
                  id="form-reset"
                  data-callback="redirect_to"
                  class="form-horizontal form-bordered form-control-borderless form-validate">
                <input type="hidden" name="action" value="reset">

                <div class="form-group">
                    <div class="col-xs-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="gi gi-envelope"></i></span>
                            <input type="text" id="register-email" name="email" class="form-control input-lg"  placeholder="{{ trans('user::language.email') }}" required data-rule-email="1">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="gi gi-asterisk"></i></span>
                            <input type="password" id="password" name="password" class="form-control input-lg" placeholder="{{ trans('user::language.password') }}" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="gi gi-asterisk"></i></span>
                            <input type="password" id="register-password-verify" name="password_confirmation" class="form-control input-lg" placeholder="{{ trans('user::language.password_confirmation') }}" data-rule-equalTo="#password">
                        </div>
                    </div>
                </div>
                <div class="form-group form-actions">
                    <div class="col-xs-6">
                    </div>
                    <div class="col-xs-6 text-right">
                        <button type="submit" class="btn btn-sm btn-success">{{ trans('auth::language.reset_password_button') }}</button>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12 text-center">
                        <small>{{ trans('auth::language.do_u_have_account') }}</small> <a href="{{ url('auth') }}" id="link-register"><small>{{ trans('auth::language.login') }}</small></a>
                    </div>
                </div>
            </form>
        </div>
        <!-- END Login Block -->
    </div>
    <!-- END Login Container -->

@stop