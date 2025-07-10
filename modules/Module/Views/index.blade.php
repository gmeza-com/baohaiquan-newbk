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
                    <th>{{ trans('module::language.current_version') }}</th>
                    <th>{{ trans('module::language.newest_version') }}</th>
                    <th>-</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($modules as $module)
                    <tr
                        class="{{ floatval($module->version) < floatval($module->latest_version) ? 'danger' : ($module->status ? '' : 'warning') }}">
                        <td class="text-center">
                            <img src="{{ $module->thumbnail }}" class="img-circle" width="80px">
                        </td>
                        <td>
                            <h3>{{ $module->name }}</h3>
                            <p>{{ $module->description }}</p>
                        </td>
                        <td>
                            <span {!! floatval($module->version) < floatval($module->latest_version) ? 'class="label label-danger"' : '' !!}>
                                {{ $module->version }}
                            </span>
                        </td>
                        <td>
                            <span {!! floatval($module->version) < floatval($module->latest_version) ? 'class="label label-success"' : '' !!}>
                                {{ $module->latest_version }}
                            </span>
                        </td>
                        <td>
                            @if (!$module->system)
                                @if (!$module->status)
                                    <button class="btn btn-sm btn-success module-action"
                                        data-url="{{ route('admin.module.activate', $module->slug) }}" data-active="true">
                                        <i class="fa fa-check-circle-o"></i> {{ trans('module::language.activate') }}
                                    </button>
                                    <button class="btn btn-sm btn-danger module-action"
                                        data-url="{{ route('admin.module.destroy', $module->slug) }}" data-active="false">
                                        <i class="fa fa-trash-o"></i> {{ trans('module::language.remove') }}
                                    </button>
                                @else
                                    <button class="btn btn-sm btn-warning module-action"
                                        data-url="{{ route('admin.module.deactivate', $module->slug) }}" data-active="false">
                                        <i class="fa fa-ban"></i> {{ trans('module::language.deactivate') }}
                                    </button>
                                @endif
                                {{-- @if (floatval($module->version) < floatval($module->latest_version)) --}}
                                {{-- <button href="javascript:alert('Chức năng này chưa hoàn thiện');" class="btn btn-sm btn-info"> --}}
                                {{-- <i class="fa fa-refresh fa-spin"></i> {{ trans('module::language.update') }} --}}
                                {{-- </button> --}}
                                {{-- @endif --}}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endcomponent

@stop

@push('footer')
    <script src="/assets/js/pages/module.js"></script>
@endpush
