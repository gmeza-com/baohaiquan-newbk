@extends('admin')

@section('page_header')

    @can('royalty.category.create')
        <div class="pull-right">
            <a href="{{ admin_route('royalty.category.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> {{ trans('language.create') }}
            </a>
        </div>
    @endcan

    <h1>{{ $title }}</h1>
@stop

@section('content')

    @component('components.block')
        @slot('title', trans('royalty::language.category_list'))
        @include('partial.datatable')
    @endcomponent

@stop
