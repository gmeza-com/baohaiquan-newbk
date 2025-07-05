@extends('admin')

@section('page_header')
    <h1>{{ $title }}</h1>
@stop

@section('content')
    <iframe class="block" style="flex-grow: 1; margin-bottom: 0" src="/assets/vendor/tinymce/plugins/moxiemanager/index.php"
        frameborder="0" width="100%" height="100%"></iframe>
@stop
