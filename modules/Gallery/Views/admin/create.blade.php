@extends('admin')

@section('page_header')

    <div class="pull-right">
        <a href="{{ admin_route('gallery.index') }}" class="btn btn-default">
            <i class="fa fa-arrow-circle-left"></i> {{ trans('language.back') }}
        </a>
        <button type="button" class="btn btn-primary" id="save-gallery-btn">
            <i class="fa fa-save"></i> {{ trans('language.save') }}
        </button>
    </div>

    <h1>
        {{ $title }}
    </h1>
@stop

@section('content')
    {!! Form::open([
        'url' => admin_route('gallery.store'),
        'method' => 'POST',
        'class' => 'form-validate',
        'id' => 'save',
        'data-callback' => 'redirect_to',
    ]) !!}
    @include('gallery::admin.form')
    {!! Form::close() !!}
@stop
