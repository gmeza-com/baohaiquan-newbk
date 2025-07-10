@extends('admin')

@section('page_header')
    <h1>
        {{ $title }}
    </h1>
@stop

@section('content')
    @foreach ($dashboard_blocks as $block)
        @include($block)
    @endforeach
@stop
