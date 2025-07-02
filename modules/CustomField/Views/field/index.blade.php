@extends('admin')

@section('page_header')

    @can('customfield.field.create')
        <div class="pull-right">
            <a href="{{ admin_route('custom-field.create') }}" class="btn btn-primary">
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

        @slot('title', trans('custom_field::language.custom_field'))

        @include('partial.datatable')
    @endcomponent

@stop
