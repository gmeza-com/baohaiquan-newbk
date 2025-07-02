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
        @foreach(get_hook('system_option_fields') as $field)
            @include($field)
        @endforeach
    </div>

    {!! Form::close() !!}

    @endcomponent

@stop