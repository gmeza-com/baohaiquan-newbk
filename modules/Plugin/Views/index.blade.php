@extends('admin')

@section('page_header')
    <h1>
        <i class="fa fa-circle-o"></i> {{ $title }}
    </h1>
@stop

@section('content')

    @component('components.block')
        @slot('title', $title)

            <table class="table table-vcenter">
                <thead>
                <tr>
                    <th style="width: 128px;" class="text-center">
                        <i class="fa fa-picture-o"></i>
                    </th>
                    <th>{{ trans('module::language.name') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($plugins as $plugin)
                    <tr>
                        <td class="text-center">
                            <img src="{{ $plugin->thumbnail }}" class="img-circle" width="80px">
                        </td>
                        <td>
                            <h3>{{ $plugin->name }}</h3>
                            <p>{{ $plugin->description }}</p>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            @endcomponent

@stop