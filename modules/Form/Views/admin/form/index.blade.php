@extends('admin')

@section('page_header')

    @can('form.form.create')
        <div class="pull-right">
            <a href="{{ admin_route('form.create') }}" class="btn btn-primary">
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
        @slot('title', trans('form::language.form_list'))

        @include('partial.datatable')
    @endcomponent

@stop
