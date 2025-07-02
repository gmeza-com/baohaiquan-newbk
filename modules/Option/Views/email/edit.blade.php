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

    <div class="row">
        <div class="col-lg-8">
            @component('components.block')

            @slot('title', $title)

            {!! Form::open([
                'url' => admin_url('option/email/' . $email_slug),
                'method' => 'POST',
                'class' => 'form-validate',
                'id' => 'save',
                'data-callback' => 'nothing_to_do'
            ]) !!}
                {{ csrf_field() }}
                <div class="form-group">
                    {!! Form::textarea('email_content', $email_content, ['class' => 'form-control editor_html']) !!}
                </div>
            {!! Form::close() !!}

            @endcomponent
        </div>

        <div class="col-lg-4">
            @component('components.block')

            @slot('title', trans('option::language.email_guide'))

                @component('components.alert')
                    @slot('type', 'info')
                    {!! trans('option::language.email_notify_view_blade_docs', ['url' => 'https://laravel.com/docs/master/blade']) !!}
                @endcomponent

                @include($email['guide'])

            @endcomponent
        </div>
    </div>

@stop

@include('partial.editor')