@extends('admin')

@section('page_header')

    @can('gallery.gallery.create')
        <div class="pull-right">
            <a href="{{ admin_route('gallery.category.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> {{ trans('language.create') }}
            </a>
        </div>
    @endcan

    <h1>
        {{ $title }}
    </h1>
@stop

@section('content')

    @component('components.block')
        @slot('title', trans('gallery::language.category_list'))

        @include('partial.datatable')
    @endcomponent

@stop
