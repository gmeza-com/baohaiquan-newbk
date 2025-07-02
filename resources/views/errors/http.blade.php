@extends('layout')

@section('layout')

    <!-- Error Container -->
    <div id="error-container">
        <div class="error-options">
            <h3><i class="fa fa-chevron-circle-left text-muted"></i> <a href="{{ url('/') }}">{{ trans('language.go_back_to_home_page') }}</a></h3>
        </div>
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 text-center">
                <h1 class="animation-pulse"><i class="fa fa-exclamation-circle text-warning"></i></h1>
                <h2 class="h3">{{ $message }}</h2>
            </div>
        </div>
    </div>
    <!-- END Error Container -->

@stop