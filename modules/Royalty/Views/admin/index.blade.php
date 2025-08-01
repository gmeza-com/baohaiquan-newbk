@extends('admin')

@section('page_header')

    <div class="pull-right">
        @can('royalty.royalty.create')
            <a href="{{ admin_route('royalty.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> {{ trans('language.create') }}
            </a>
        @endcan
        @can('royalty.royalty.export')
            <button id="btn-export-royalty" data-route="{{ admin_route('royalty.royalty.export') }}" class="btn btn-primary">
                <i class="fa fa-download"></i> Export to CSV
            </button>
        @endcan
    </div>

    <h1>{{ $title }}</h1>
@stop

@php
    $year = date('Y');
    $month = date('m');

    $filterMonth = explode('/', request()->get('month'));
    if (is_array($filterMonth) && count($filterMonth) == 2) {
        $year = $filterMonth[1];
        $month = $filterMonth[0];
    }
@endphp

@section('content')

    @component('components.block')
        @slot('title', trans('royalty::language.royalty_list'))

        @slot('action')
            <div style="display:flex;flex-wrap:wrap;justify-content:flex-end;">
                <div class="dropdown">
                    <button class="form-control" type="button" style="margin-right: 5px; width: 130px;" id="dropdownMenu1"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        @if (request()->get('quater') != '')
                            {{ request()->get('quater') }}
                        @else
                            Theo quý
                        @endif
                        <i class="fa fa-calendar" style="margin-left: 5px"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenu1" style="padding: 6px">
                        <div class="row" style="margin-bottom: 5px; min-width: 240px">
                            <div class="col-lg-6" style="margin-bottom: 10px">
                                <label>Năm</label>
                                <select id="input-year" class="form-control non-select2">
                                    @for ($i = date('Y'); $i >= 2024; $i--)
                                        <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-lg-6" style="margin-bottom: 10px">
                                <label>Quý</label>
                                <select id="input-quater" class="form-control non-select2">
                                    @for ($i = 1; $i <= 4; $i++)
                                        <option value="{{ $i < 10 ? '0' . $i : $i }}" {{ $i == $month ? 'selected' : '' }}>
                                            {{ $i < 10 ? '0' . $i : $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <button type="button" id="filter-quater" class="btn btn-default btn-sm btn-block">Xem</button>
                        <button type="button" id="cancel-filter-quater" class="btn btn-default btn-sm btn-block">Hủy lọc
                            quý</button>
                    </div>
                </div>

                <div class="dropdown">
                    <button class="form-control" type="button" style="margin-right: 5px; width: 130px;" id="dropdownMenu1"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        @if (request()->get('month') != '')
                            {{ request()->get('month') }}
                        @else
                            Theo tháng
                        @endif
                        <i class="fa fa-calendar" style="margin-left: 5px"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenu1" style="padding: 6px">
                        <div class="row" style="margin-bottom: 5px; min-width: 240px">
                            <div class="col-lg-6" style="margin-bottom: 10px">
                                <label>Năm</label>
                                <select id="input-year" class="form-control non-select2">
                                    @for ($i = date('Y'); $i >= 2024; $i--)
                                        <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-lg-6" style="margin-bottom: 10px">
                                <label>Tháng</label>
                                <select id="input-month" class="form-control non-select2">
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i < 10 ? '0' . $i : $i }}" {{ $i == $month ? 'selected' : '' }}>
                                            {{ $i < 10 ? '0' . $i : $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <button type="button" id="filter-month" class="btn btn-default btn-sm btn-block">Xem</button>
                        <button type="button" id="cancel-filter-month" class="btn btn-default btn-sm btn-block">Hủy lọc
                            tháng</button>
                    </div>
                </div>
                <form action="{{ request()->url() }}" method="GET" id="filters" style="display:flex;">
                    <input type="hidden" id="month" name="month" value="{{ request()->get('month') }}">
                    <select name="category" id="flter_by_category" class="form-control non-select2"
                        style="min-width: 120px; max-width:320px; margin-right: 5px">
                        <option value="*">{{ trans('royalty::language.include_category') }}</option>
                        @foreach (get_all_royalty_category() as $category)
                            <option value="{{ $category->id }}"
                                {{ $category->id == request()->get('category') ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <select name="status" id="flter_by_status" class="form-control non-select2"
                        style="min-width: 120px; max-width:320px">
                        <option value="*">{{ trans('royalty::language.include_status') }}</option>
                        @foreach (get_all_royalty_status() as $status)
                            <option value="{{ $status->id }}" {{ $status->id == request()->get('status') ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>

                    <div class="form_group" style="margin-left: 4px;">
                        {!! Form::select(
                            'user_id',
                            ['' => 'Tất cả người nhận'] + get_list_authors_for_choose(),
                            request()->get('user_id') ? request()->get('user_id') : '*',
                            [
                                'class' => 'form-control',
                                'multiple' => false,
                                'style' => 'min-width: 220px; max-width:320px',
                                'id' => 'filter_by_user_id',
                            ],
                        ) !!}
                    </div>


                </form>
            </div>
            <div class="clearfix"></div>
        @endslot

        @include('partial.datatable')
    @endcomponent

@stop

@push('footer')
    <script>
        $('#flter_by_category, #flter_by_status, #filter_by_user_id').change(function(e) {
            $('#filters').trigger('submit');
        });

        $(document).on('click', '.dropdown-menu', function(event) {
            event.stopPropagation(); // Prevent the dropdown from closing
        });

        $('#filter-month').click(function() {
            var month = $('#input-month').val();
            var year = $('#input-year').val();
            if (month && year) {
                $('#month').val(month + '/' + year);
                $('#filters').trigger('submit');
            }
        });

        $('#cancel-filter-month').click(function() {
            $('#month').val('');
            $('#filters').trigger('submit');
        });

        $('#btn-export-royalty').click(function() {
            var route = $(this).data('route');
            // change the action of #filters form to the export route then submit
            $('#filters').attr('action', route).submit();

            // reload page
            $('#filters').attr('action', '{{ request()->url() }}');
        });
    </script>
@endpush
