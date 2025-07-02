@extends('admin')

@section('page_header')

    <div class="pull-right">
        <a href="{{ admin_route('acl.permission.index') }}" class="btn btn-default">
            <i class="fa fa-arrow-circle-left"></i> {{ trans('language.back') }}
        </a>
        <button type="button" class="btn btn-primary" onclick="submitForm('#permission_create');">
            <i class="fa fa-save"></i> {{ trans('language.save') }}
        </button>
    </div>

    <h1>
        {{ $title }}
    </h1>
@stop

@section('content')
    {!! Form::open([
        'url' => admin_route('acl.permission.store'),
        'method' => 'POST',
        'class' => 'form-validate',
        'id' => 'permission_create'
    ]) !!}

    @include('acl::permission.form')
    {!! Form::close() !!}
@stop
