@extends('admin')

@section('page_header')

    <div class="pull-right">
        <button type="button" class="btn btn-primary" onclick="submitForm('#save');">
            <i class="fa fa-save"></i> {{ trans('language.save') }}
        </button>
    </div>

    <h1>
        {{ $title }}
    </h1>
@stop

@section('content')

    @component('components.block')

    @slot('title', $title)

    {!! Form::open([
        'url' => admin_url('option'),
        'method' => 'POST',
        'class' => 'form-validate form-horizontal',
        'id' => 'save',
        'enctype' => 'multipart/form-data',
        'data-callback' => 'nothing_to_do'
    ]) !!}
    {{ csrf_field() }}

    <div class="row">
        <div class="col-lg-4">
            <h2>{{ trans('option::language.general_information') }}</h2>
            <p>{{ trans('option::language.general_information_des') }}</p>
        </div>
        <div class="col-lg-8">
            <div class="row">
                <div class="col-md-12">
                    <div class="form_group">
                        {!! Form::label('site_name', trans('option::language.general_site_name'), ['class' => 'control-label']) !!}
                        {!! Form::text('site_name', get_option('site_name'), ['class' => 'form-control', 'placeholder' => trans('option::language.general_site_name'), 'required']) !!}
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form_group">
                        {!! Form::label('site_description', trans('option::language.general_site_description'), ['class' => 'control-label']) !!}
                        {!! Form::text('site_description', get_option('site_description'), ['class' => 'form-control', 'placeholder' => trans('option::language.general_site_description'), 'required']) !!}
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form_group">
                        {!! Form::label('site_keywords', trans('option::language.general_site_keywords'), ['class' => 'control-label']) !!}
                        {!! Form::text('site_keywords', get_option('site_keywords'), ['class' => 'form-control', 'placeholder' => trans('option::language.general_site_keywords'), 'required']) !!}
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form_group">
                        {!! Form::label('site_url', trans('option::language.general_site_url'), ['class' => 'control-label']) !!}
                        {!! Form::text('site_url', get_option('site_url'), ['class' => 'form-control', 'placeholder' => trans('option::language.general_site_url'), 'required']) !!}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form_group">
                        {!! Form::label('site_logo', trans('option::language.general_site_logo'), ['class' => 'control-label']) !!}
                        <div class="choose-thumbnail">
                            {!! Form::hidden('site_logo', get_option('site_logo'), ['id' => 'site_logo']) !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form_group">
                        {!! Form::label('site_favicon', trans('option::language.general_site_favicon'), ['class' => 'control-label']) !!}
                        <div class="choose-thumbnail">
                            {!! Form::hidden('site_favicon', get_option('site_favicon'), ['id' => 'site_favicon']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-4">
            <h2>{{ trans('option::language.general_address') }}</h2>
            <p>{{ trans('option::language.general_address_des') }}</p>
        </div>
        <div class="col-lg-8">
            <div class="row">
                <div class="col-md-12">
                    <div class="form_group">
                        {!! Form::label('site_business_license', trans('option::language.general_site_business_license'), ['class' => 'control-label']) !!}
                        {!! Form::text('site_business_license', get_option('site_business_license'), ['class' => 'form-control', 'placeholder' => trans('option::language.general_site_business_license')]) !!}
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form_group">
                        {!! Form::label('site_email', trans('option::language.general_site_email'), ['class' => 'control-label']) !!}
                        {!! Form::text('site_email', get_option('site_email'), ['class' => 'form-control', 'placeholder' => trans('option::language.general_site_email')]) !!}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form_group">
                        {!! Form::label('site_phone', trans('option::language.general_site_phone'), ['class' => 'control-label']) !!}
                        {!! Form::text('site_phone', get_option('site_phone'), ['class' => 'form-control', 'placeholder' => trans('option::language.general_site_phone')]) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form_group">
                        {!! Form::label('site_fax', trans('option::language.general_site_fax'), ['class' => 'control-label']) !!}
                        {!! Form::text('site_fax', get_option('site_fax'), ['class' => 'form-control', 'placeholder' => trans('option::language.general_site_fax')]) !!}
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form_group">
                        {!! Form::label('site_address', trans('option::language.general_site_address'), ['class' => 'control-label']) !!}
                        {!! Form::text('site_address', get_option('site_address'), ['class' => 'form-control', 'placeholder' => trans('option::language.general_site_address')]) !!}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form_group" id="province" data-name="{{ get_option('province') ?: 0 }}"></div>
                </div>
                <div class="col-md-6">
                    <div class="form_group" id="district" data-name="{{ get_option('district') ?: 0 }}"></div>
                </div>

                <div class="col-md-12">
                    <div class="form_group">
                        {!! Form::label('site_view', trans('option::language.general_site_view'), ['class' => 'control-label']) !!}
                        {!! Form::text('site_view', get_option('site_view'), ['class' => 'form-control', 'placeholder' => trans('option::language.general_site_view')]) !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-4">
            <h2>{{ trans('option::language.general_codescript') }}</h2>
            <p>{{ trans('option::language.general_codescript_des') }}</p>
        </div>
        <div class="col-lg-8">
            <div class="row">
                <div class="col-md-6">
                    <div class="form_group">
                        {!! Form::label('google_analytics', 'Google Analytics', ['class' => 'control-label']) !!}
                        {!! Form::textarea('google_analytics', get_option('google_analytics'), ['class' => 'form-control', 'placeholder' => 'Google Analytics']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form_group">
                        {!! Form::label('google_remaketing', 'Google Remaketing', ['class' => 'control-label']) !!}
                        {!! Form::textarea('google_remaketing', get_option('google_remaketing'), ['class' => 'form-control', 'placeholder' => 'Google Remaketing']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form_group">
                        {!! Form::label('facebook_pixel', 'Facebook Pixel', ['class' => 'control-label']) !!}
                        {!! Form::textarea('facebook_pixel', get_option('facebook_pixel'), ['class' => 'form-control', 'placeholder' => 'Facebook Pixel']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form_group">
                        {!! Form::label('livechat', 'Livechat', ['class' => 'control-label']) !!}
                        {!! Form::textarea('livechat', get_option('livechat'), ['class' => 'form-control', 'placeholder' => 'Livechat']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>
    <div class="row">
        <div class="col-lg-4">
            <h2>{{ trans('option::language.general_mail') }}</h2>
            <p>{{ trans('option::language.general_mail_des') }}</p>
        </div>
        <div class="col-lg-8">
            <div class="row">
                <div class="col-md-6">
                    <div class="form_group">
                        {!! Form::label('mail_host', trans('option::language.general_mail_host'), ['class' => 'control-label']) !!}
                        {!! Form::text('mail_host', get_option('mail_host'), ['class' => 'form-control', 'placeholder' => trans('option::language.general_mail_host')]) !!}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form_group">
                        {!! Form::label('mail_port', trans('option::language.general_mail_port'), ['class' => 'control-label']) !!}
                        {!! Form::text('mail_port', get_option('mail_port'), ['class' => 'form-control', 'placeholder' => trans('option::language.general_mail_port')]) !!}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form_group">
                        {!! Form::label('mail_from_address', trans('option::language.general_mail_from_address'), ['class' => 'control-label']) !!}
                        {!! Form::email('mail_from_address', get_option('mail_from_address'), ['class' => 'form-control', 'placeholder' => trans('option::language.general_mail_from_address')]) !!}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form_group">
                        {!! Form::label('mail_from_name', trans('option::language.general_mail_from_name'), ['class' => 'control-label']) !!}
                        {!! Form::text('mail_from_name', get_option('mail_from_name'), ['class' => 'form-control', 'placeholder' => trans('option::language.general_mail_from_name')]) !!}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form_group">
                        {!! Form::label('mail_username', trans('option::language.general_mail_username'), ['class' => 'control-label']) !!}
                        {!! Form::text('mail_username', get_option('mail_username'), ['class' => 'form-control', 'placeholder' => trans('option::language.general_mail_username')]) !!}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form_group">
                        {!! Form::label('mail_password', trans('option::language.general_mail_password'), ['class' => 'control-label']) !!}
                        {!! Form::text('mail_password', get_option('mail_password'), ['class' => 'form-control', 'placeholder' => trans('option::language.general_mail_password')]) !!}
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form_group">
                        {!! Form::label('mail_encryption', trans('option::language.general_mail_encryption'), ['class' => 'control-label']) !!}
                        {!! Form::select('mail_encryption', [
                            'ssl' => 'ssl',
                            'tls' => 'tls',
                            '' => 'none'
                        ], get_option('mail_encryption'), ['class' => 'form-control', 'placeholder' => trans('option::language.general_mail_encryption')]) !!}
                    </div>
                </div>

            </div>
        </div>

        @foreach(get_hook('general_option_fields') as $field)
            @include($field)
        @endforeach

    </div>

        {!! Form::close() !!}

    @endcomponent

@stop

@include('partial.editor')