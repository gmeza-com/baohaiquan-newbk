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
            <form action="{{ request()->url() }}" id="filter" method="GET">
                <select name="category" id="flter_by_category" class="form-control non-select2" style="min-width: 500px">
                    <option value="*">{{ trans('news::language.include_categories') }}</option>
                    @foreach(get_all_gallery_categories() as $category)
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
    $('#flter_by_category').change(function (e) {
        $('#filter').trigger('submit');
    });
</script>
@endpush
