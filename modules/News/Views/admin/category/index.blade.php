@extends('admin')

@section('page_header')

    @can('news.category.create')
        <div class="pull-right">
            <a href="{{ admin_route('post.category.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> {{ trans('language.create') }}
            </a>
        </div>
    @endcan

    <h1>
        {{ $title }}
    </h1>
@stop

@section('content')
    @include('partial.datatable_mutillang', ['url' => admin_route('post.category.index')])

    @component('components.block')
        @slot('title', trans('news::language.category_list'))

        @include('partial.datatable')
    @endcomponent

@stop
