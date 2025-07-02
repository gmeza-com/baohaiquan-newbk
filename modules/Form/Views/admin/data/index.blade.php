@extends('admin')

@section('page_header')

    <h1>
        {{ $title }}
    </h1>
@stop

@section('content')

    @component('components.block')

        @slot('title', trans('form::language.form_data'))

        @include('partial.datatable')
    @endcomponent

@stop