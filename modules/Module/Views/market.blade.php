@extends('admin')

@push('footer')
<script>
    $('.install').click(function (event) {
        event.preventDefault();

        if($(this).data('customized') === true) {
            swal(CNV.language.warning, '{{ trans('module::language.module_has_customized') }} ', 'warning');
            return;
        }

        if($(this).data('version') === false) {
            swal(CNV.language.warning, '{{ trans('module::language.required_version') }} ' + $(this).data('version-required') + ' {{ trans('module::language.but_your_version_is') }} {{ config('cnv.current_version') }}', 'warning');
            return;
        }

        if($(this).data('message') !== '') {
            swal(CNV.language.warning, $(this).data('message'), 'warning');
            return;
        }

        if($(this).data('exists') === true) {
            var btn = $(this);

            swal({
                title: CNV.language.warning,
                text: '{{ trans('module::language.module_has_exits') }}',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'OK',
                cancelButtonText: 'cancel'
            }).then(function () {
                window.location.href = btn.data('href');
            });
            return;
        }

        window.location.href = $(this).data('href');
    });
</script>
@endpush

@section('page_header')
    <h1>
        <i class="fa fa-shopping-cart"></i> {{ $title }}
    </h1>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                @foreach($modules['data'] as $module)
                <div class="col-sm-4">
                    <div class="widget">
                        <div class="widget-advanced">
                            <div class="widget-header text-center themed-background-dark-default">
                                <div class="widget-options">
                                    <span class="label label-info">Version {{ $module['version'] }}</span>
                                </div>
                                <h3 class="widget-content-light">
                                    <a href="javascript:void(0);" class="themed-color-default">{{ $module['name'] }}</a><br>
                                    <small>{{ $module['description'] }}</small>
                                </h3>
                            </div>
                            <div class="widget-main">
                                <a href="javascript:void(0);" class="widget-image-container animation-fadeIn">
                                    <img src="{{ $module['thumbnail'] }}" class="img-circle" style="max-width: 100%">
                                </a>
                                <a href="javascript:void(0);"
                                   data-exists="{{ app('module')->hasModules($module['slug']) ? 'true' : 'false' }}"
                                   data-customized="{{ app('module')->hasCustomized($module['slug']) ? 'true' : 'false' }}"
                                   data-version="{{ version_compare(config('cnv.current_version'), $module['required_version'], '>=') ? 'true' : 'false' }}"
                                   data-version-required="{{ $module['required_version'] }}"
                                   data-message="{{
                                       !app('module')->hasModules($module['required_modules']) ?
                                            trans('module::language.requred_modules', ['modules' => implode(', ', $module['required_modules'])]) : ''
                                   }}"
                                   data-href="{{ admin_url('module/install?slug=' . $module['slug'] . '&price=' . $module['price'] ) }}"
                                   class="install btn btn-sm btn-{{ $module['price'] > 0 ? 'danger' : 'info' }} pull-right">
                                    <i class="fa fa-{{ $module['price'] > 0 ? 'shopping-cart' : 'cloud-download' }}"></i> {{ $module['price'] == 0 ? trans('module::language.install') :  trans('module::language.buy') }}
                                </a>
                                <a href="javascript:void(0);" class="btn btn-sm btn-success">
                                    {!! $module['price'] > 0 ? number_format($module['price']) . '<sup>đ</sup>' : trans('language.free') !!}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <ul class="pagination">
                        @if($modules['prev_page'])
                            <li>
                                <a href="{{ url()->current() }}?page={{ $page-1 }}">
                                    {{ trans('pagination.previous') }}
                                </a>
                            </li>
                        @endif
                        @if($modules['next_page'])
                            <li>
                                <a href="{{ url()->current() }}?page={{ $page+1 }}">
                                    {{ trans('pagination.next') }}
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

@stop