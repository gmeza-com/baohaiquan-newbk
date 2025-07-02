@extends('admin')

@section('page_header')

    <div class="pull-right">
        <a href="{{ route('admin.acl.role.index') }}" class="btn btn-default">
            <i class="fa fa-arrow-circle-left"></i> {{ trans('language.back') }}
        </a>
        <button type="button" class="btn btn-primary" onclick="submitForm('#role_edit');">
            <i class="fa fa-save"></i> {{ trans('language.save') }}
        </button>
    </div>

    <h1>
        {{ $title }}
    </h1>
@stop

@section('content')
    {!! Form::open([
        'url' => admin_route('acl.role.update', $role->id),
        'method' => 'put',
        'class' => 'form-validate',
        'id' => 'role_edit',
        'data-callback' => 'nothing_to_do'
    ]) !!}

    @include('acl::role.form')
    {!! Form::close() !!}
@stop
