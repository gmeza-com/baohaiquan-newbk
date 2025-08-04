@extends('admin')

@section('page_header')

    @can('gallery.gallery.create')
        <div class="pull-right">
            <a href="{{ admin_route('gallery.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> {{ trans('language.create') }}
            </a>
        </div>
    @endcan

    <h1>
        {{ $title }}
    </h1>
@stop

@section('content')
    @include('partial.datatable_mutillang', ['url' => admin_route('gallery.index')])

    @component('components.block')
        @slot('title', trans('gallery::language.gallery_list'))

        @slot('action')
            <form action="{{ request()->url() }}" id="filter" method="GET" style="display:flex">
                <select name="has_royalty" id="filter_by_has_royalty" class="form-control non-select2"
                    style="min-width: 60px; max-width:260px; margin-right: 5px">
                    <option value="*" {{ request()->has('has_royalty') && request('has_royalty') == '*' ? 'selected' : '' }}>
                        Tất cả trạng thái nhuận bút
                    </option>
                    <option value="1" {{ request()->has('has_royalty') && request('has_royalty') == 1 ? 'selected' : '' }}>
                        Có nhuận bút
                    </option>
                    <option value="0" {{ request()->has('has_royalty') && request('has_royalty') == 0 ? 'selected' : '' }}>
                        Không nhuận bút
                    </option>
                </select>

                @if (!$is_waiting_approve_post)
                    <select name="approve_level" id="filter_by_status" class="form-control non-select2"
                        style="min-width: 60px; max-width:160px; margin-right: 5px">
                        <option value="*"
                            {{ request()->has('approve_level') && request('approve_level') == '*' ? 'selected' : '' }}>
                            Tất cả trạng thái
                        </option>
                        <option value="3"
                            {{ request()->has('approve_level') && request('approve_level') == 3 ? 'selected' : '' }}>
                            {{ trans('news::language.approved_by_level_3') }}
                        </option>
                        <option value="0"
                            {{ request()->has('approve_level') && request('approve_level') == 0 ? 'selected' : '' }}>
                            {{ trans('news::language.waiting_level_1') }}
                        </option>
                        <option value="1"
                            {{ request()->has('approve_level') && request('approve_level') == 1 ? 'selected' : '' }}>
                            {{ trans('news::language.waiting_level_2') }}
                        </option>
                        <option value="2"
                            {{ request()->has('approve_level') && request('approve_level') == 2 ? 'selected' : '' }}>
                            {{ trans('news::language.waiting_level_3') }}
                        </option>
                        <option value="-1"
                            {{ request()->has('approve_level') && request('approve_level') == -1 ? 'selected' : '' }}>
                            Đã hủy
                        </option>
                    </select>
                @endif

                <select name="category" id="flter_by_category" class="form-control non-select2"
                    style="min-width: 120px; max-width:320px">
                    <option value="*">{{ trans('news::language.include_categories') }}</option>
                    @foreach (get_all_gallery_categories() as $category)
                        <option value="{{ $category->id }}" {{ $category->id == request()->get('category') ? 'selected' : '' }}>
                            {{ $category->language('name') }}
                        </option>
                    @endforeach
                </select>
            </form>
            <div class="clearfix"></div>
        @endslot

        @include('partial.datatable')
    @endcomponent

@stop

@push('footer')
    <script>
        $('#flter_by_category,#filter_by_has_royalty,#filter_by_status').change(function(e) {
            $('#filter').trigger('submit');
        });
    </script>
@endpush
