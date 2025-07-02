@extends('admin')

@section('page_header')
    <h1>
        {{ $title }}
    </h1>
@stop

@section('content')
    @include('partial.datatable_mutillang', ['url' => admin_route('post.revision.index')])

    @component('components.block')

        @slot('title', 'Danh sách lịch sử bài viết')


        <div class="clearfix" style="margin-bottom: 30px"></div>

        @include('partial.datatable')
    @endcomponent
@stop

