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
            <p class="alert alert-success">
                {!! trans('auth::language.message_activation', ['url' => url('auth')]) !!}
            </p>
        </div>
        <!-- END Login Block -->
    </div>
    <!-- END Login Container -->

@stop