@extends('admin')

@section('page_header')

    <div class="pull-right">
        <a href="{{ admin_route('post.index') }}" class="btn btn-default">
            <i class="fa fa-arrow-circle-left"></i> {{ trans('language.back') }}
        </a>
        @if (!$read_only)
            <button type="button" class="btn btn-primary" onclick="submitForm('#save');">
                <i class="fa fa-save"></i> {{ trans('language.save') }}
            </button>
        @endif

    </div>

    <h1>{{ $title }}</h1>

    @if ($read_only)
        <div class="alert alert-warning post-edit-read-only">
            <p>Bài viết đã được duyệt, không thể chính sửa</p>
        </div>
    @endif
@stop



@section('content')
    {!! Form::open([
        'url' => admin_route('post.update', $post->id),
        'method' => 'POST',
        'class' => 'form-validate form-edit-post',
        'id' => 'save',
        'data-callback' => 'nothing_to_do',
    ]) !!}
    {!! method_field('PUT') !!}

    @include('news::admin.post.form')

    @if ($read_only)
        <div class="disabled-form-overlay" />
    @endif

    {!! Form::close() !!}
@stop
