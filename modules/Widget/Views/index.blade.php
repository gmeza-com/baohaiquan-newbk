@extends('admin')

@section('page_header')

    @can('widget.widget.create')
        <div class="pull-right">
            <a href="{{ admin_route('widget.create') }}" class="btn btn-primary">
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

        @slot('title', trans('widget::language.widget_list'))

        @include('partial.datatable')
    @endcomponent

@stop

@push('footer')
<script>
    "use strict";

    var lock_widget = function (event, btn) {
        event.preventDefault();
        btn.button('loading');

        $.post(btn.data('url'), {
            _method: 'PUT',
            lock: true
        }, function (response) {
            if(response.status == 200) {
                toastr.success(response.message, CNV.language.success);
                TablesDatatables.table._fnAjaxUpdate();
            } else if(response.status == 500) {
                toastr.warning(response.message, CNV.language.warning);
            } else {
                toastr.error(CNV.language.unknown_error, CNV.language.error);
            }
            btn.button('reset');
        });

        return false;
    }
</script>
@endpush