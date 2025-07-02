@extends('admin')

@section('page_header')
    <h1>
        <i class="fa fa-plug"></i> {{ $title }}
    </h1>
@stop

@section('content')

    @component('components.block')

        @slot('title', $title)

            @include('partial.datatable')
            @endcomponent

@stop
