@extends('admin')

@section('page_header')

    <div class="pull-right">
        <a href="{{ admin_route('royalty.index') }}" class="btn btn-default">
            <i class="fa fa-arrow-circle-left"></i> {{ trans('language.back') }}
        </a>
        <button type="button" class="btn btn-primary" onclick="submitForm('#save');">
            <i class="fa fa-save"></i> {{ trans('language.save') }}
        </button>
    </div>

    <h1>
        {{ $title }}
    </h1>
@stop

@section('content')
    {!! Form::open([
        'url' => admin_route('royalty.update', $royalty->id),
        'method' => 'POST',
        'class' => 'form-validate',
        'id' => 'save',
        'data-callback' => 'nothing_to_do',
    ]) !!}
    {!! method_field('PUT') !!}
    @include('royalty::admin.form')
    {!! Form::close() !!}
@stop
