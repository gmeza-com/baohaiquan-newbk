@extends('admin')

@section('page_header')

    <div class="pull-right">
        <a href="{{ route('admin.menu.index') }}" class="btn btn-default">
            <i class="fa fa-arrow-circle-left"></i> {{ trans('language.back') }}
        </a>
    </div>

    <h1>
        {{ $title }}
    </h1>
@stop

@section('content')
    @component('components.block')
        @slot('footer', true)
        @slot('title', trans('language.create'))

        {!! Form::open([
            'url' => admin_route('menu.store'),
            'method' => 'POST',
            'class' => 'form-horizontal form-bordered form-validate'
        ]) !!}

        @include('menu::form')
        {!! Form::close() !!}
    @endcomponent
@stop
