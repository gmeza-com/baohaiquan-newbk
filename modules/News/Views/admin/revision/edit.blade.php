@extends('admin')

@section('page_header')

    <div class="pull-right">
        <a href="{{ admin_route('post.revision.index') }}" class="btn btn-default">
            <i class="fa fa-arrow-circle-left"></i> {{ trans('language.back') }}
        </a>
        <button type="button" class="btn btn-danger" onclick="submitForm('#save');">
            <i class="fa fa-undo"></i> Khôi phục
        </button>
    </div>

    <h1>
        {{ $title }}
    </h1>
@stop

@section('content')
    {!! Form::open([
        'url' => admin_route('post.revision.update', $postHistory->id),
        'method' => 'POST',
        'class' => 'form-validate',
        'id' => 'save',
        'data-callback' => 'redirect_to',
    ]) !!}
    {!! method_field('PUT') !!}
    @include('news::admin.revision.form')
    {!! Form::close() !!}
@stop
