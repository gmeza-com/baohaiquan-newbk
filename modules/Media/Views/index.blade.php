@extends('admin')

@section('page_header')
    <h1>
        <i class="fa fa-film"></i> {{ $title }}
    </h1>
@stop

@section('content')
    <div style="height: 580px; margin: -20px">
        <iframe src="/assets/vendor/tinymce/plugins/moxiemanager/index.php" frameborder="0" width="100%" height="100%"></iframe>
    </div>
@stop