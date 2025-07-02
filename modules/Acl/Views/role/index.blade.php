@extends('admin')

@section('page_header')

    @can('acl.role.create')
    <div class="pull-right">
        <a href="{{ admin_route('acl.role.create') }}" class="btn btn-primary">
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

        @slot('title', trans('acl::language.role_list'))

        @include('partial.datatable')
    @endcomponent

@stop
