@push('header')
    <link rel="stylesheet" href="/backend/css/longform.css">
    <link rel="stylesheet" href="/backend/css/editorjs-render.css">
@endpush

@php
    $royalty = $gallery
        ->royalties()
        ->whereIn('status_id', [1, 2, 3])
        ->get()
        ->first();
@endphp

<meta name="gallery-type" content="{{ @$gallery->type }}">
<div class="row">
    <div class="col-lg-8">
        @component('components.block')
            @slot('title', trans('language.basic_info'))
            <div class="block-body">
                <div class="form-bordered">
                    <ul class="nav nav-tabs" data-toggle="tabs">
                        @foreach (config('cnv.languages') as $language)
                            <li {{ $loop->first ? 'class=active' : '' }}>
                                <a href="#{{ $language['locale'] }}">
                                    {{ $language['name'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content">
                        @foreach (config('cnv.languages') as $language)
                            <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="{{ $language['locale'] }}">
                                <div class="form-group">
                                    {!! Form::label('name', trans('language.name'), ['class' => 'label-control']) !!}
                                    {!! Form::text('language[' . $language['locale'] . '][name]', @$gallery->language('name', $language['locale']), [
                                        'class' => 'form-control',
                                        'required',
                                    ]) !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::label('description', trans('language.description'), ['class' => 'label-control']) !!}
                                    {!! Form::textarea(
                                        'language[' . $language['locale'] . '][description]',
                                        @$gallery->language('description', $language['locale']),
                                        ['class' => 'form-control', 'required'],
                                    ) !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endcomponent


        @include('seo_plugin::form', [
            'base' => @$gallery->categories->first()
                ? @$gallery->categories->first()->language('slug', config('cnv.languages')[0]['locale'])
                : ':slug',
            'model' => $gallery,
        ])
        <div id="form-gallery"></div>

    </div>
    <div class="col-lg-4">
        @component('components.block')
            @slot('title', trans('language.setting_field'))
            <div class="block-body">
                <div class="form-horizontal form-bordered">
                    @if (!$gallery->type)
                        <div class="form-group">
                            {!! Form::label('type', trans('gallery::language.type'), ['class' => 'control-label col-md-4']) !!}
                            <div class="col-md-8">
                                {!! Form::select(
                                    'type',
                                    ['album' => 'Album', 'video' => 'Video', 'audio' => 'Audio', 'longform' => 'Longform'],
                                    null,
                                    [
                                        'class' => 'form-control',
                                    ],
                                ) !!}
                            </div>
                        </div>
                    @endif

                    <div class="form-group">
                        {!! Form::label('featured', trans('gallery::language.featured'), ['class' => 'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            <label class="switch switch-warning">
                                <input type="checkbox" name="featured" value="1"
                                    {{ @$gallery->featured ? 'checked' : '' }}>
                                <span></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('published', trans('language.published'), ['class' => 'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            <label class="switch switch-primary">
                                <input type="checkbox" name="published" value="1"
                                    {{ @$gallery->published ? 'checked' : '' }}>
                                <span></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <a href="javascript:void(0);"
                            onclick="toggleThisElement('#show_publish_datetime');return false;">{{ trans('language.set_a_specific_publish_date') }}</a>
                    </div>
                    <div class="form-group" id="show_publish_datetime" style="display: none">
                        <div class="col-md-7">
                            {!! Form::text('date_published', \Carbon\Carbon::parse(@$gallery->published_at)->format('d-m-Y'), [
                                'class' => 'form-control input-datepicker',
                            ]) !!}
                        </div>
                        <div class="col-md-5">
                            <div class="input-group bootstrap-timepicker timepicker">
                                {!! Form::text('time_published', @\Carbon\Carbon::parse(@$gallery->published_at)->format('H:i'), [
                                    'class' => 'form-control input-timepicker24',
                                ]) !!}
                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcomponent

        @component('components.block')
            @slot('title', trans('gallery::language.choose_category'))
            @slot('action', link_to_route('admin.gallery.category.index', trans('gallery::language.category_create'), null,
                ['class' => 'btn btn-xs btn-primary', 'target' => '_blank', 'required']))
                <div class="block-body">
                    <div class="form_group">
                        {!! Form::select(
                            'category[]',
                            (new \Modules\Gallery\Models\GalleryCategory())->getForSelection(),
                            @$gallery->categories->map->id->toArray(),
                            ['class' => 'form-control', 'multiple' => true],
                        ) !!}
                    </div>
                </div>
            @endcomponent

            @component('components.block')
                @slot('title', trans('language.thumbnail'))
                <div class="block-body">
                    <div class="form_group">
                        <div class="choose-thumbnail">
                            {!! Form::hidden('thumbnail', $gallery->thumbnail, ['id' => 'thumbnail']) !!}
                        </div>
                    </div>
                </div>
            @endcomponent

            @if (allow('royalty.royalty.index') && allow('royalty.royalty.create'))
                @component('components.block')
                    @slot('title', trans('royalty::language.choose_category'))
                    <div class="block-body">
                        @php
                            $readonly = false;
                            if (isset($royalty) && isset($royalty->status)) {
                                $classes = ['', 'warning', 'info', 'success', 'danger'];
                                echo '<div class="alert alert-' .
                                    $classes[$royalty->status_id] .
                                    '">' .
                                    $royalty->status->name .
                                    '</div>';

                                if ($royalty->status_id == 2 || $royalty->status_id == 3) {
                                    $readonly = true;
                                }
                            }
                        @endphp
                        <div class="form-horizontal form-bordered">
                            <div class="form-group">
                                {!! Form::label('royalty', 'Có nhuận bút', ['class' => 'control-label col-md-4']) !!}
                                <div class="col-md-8">
                                    <label class="switch switch-success {{ @$readonly ? 'disabled' : '' }}">
                                        <input type="checkbox" {{ @$readonly ? 'disabled readonly' : '' }} name="add-royalty"
                                            value="1" {{ @$royalty ? 'checked' : '' }}>
                                        <span></span>
                                    </label>
                                </div>
                                <input type="hidden" name="royalty[id]" value="{{ @$royalty ? $royalty->id : 0 }}">
                                <input type="hidden" name="royalty[status_id]"
                                    value="{{ @$royalty ? $royalty->status_id : 1 }}">
                                <input type="hidden" name="royalty[amount]" value="{{ @$royalty ? $royalty->amount : 0 }}">
                            </div>
                            <div id="royalty-config-wrapper" class="{{ @$royalty ? '' : 'hide' }}">
                                <div class="form-group">
                                    {!! Form::label('royalty[user_id]', trans('royalty::language.claim_for'), [
                                        'class' => 'control-label col-md-4',
                                    ]) !!}
                                    <div class="col-md-8">
                                        {!! @$readonly
                                            ? '<div style="padding: 7px 0"><b>' . $royalty->author->name . '</b></div>'
                                            : Form::select(
                                                'royalty[user_id]',
                                                get_list_authors_for_choose(),
                                                isset($royalty) && $royalty->user_id ? $royalty->user_id : auth()->user()->id,
                                                [
                                                    'class' => 'form-control',
                                                    'style' => 'width: 100%!important',
                                                ],
                                            ) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('royalty[category_id]', trans('royalty::language.type_category'), [
                                        'class' => 'control-label col-md-4',
                                    ]) !!}
                                    <div class="col-md-8">
                                        {!! @$readonly
                                            ? '<div style="padding: 7px 0">' . $royalty->category->name . '</div>'
                                            : Form::select(
                                                'royalty[category_id]',
                                                get_list_royalty_category_to_choose(),
                                                isset($royalty) && $royalty->cateogry_id ? $royalty->cateogry_id : 0,
                                                ['class' => 'form-control', 'style' => 'width: 100%!important'],
                                            ) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcomponent
            @endif
        </div>

        @include('partial.editor')
        @include('partial.editor-js')


        @push('footer')
            <script>
                "use strict";

                (function($) {



                    var loadFormWidget = function($type) {
                        $.get('{{ request()->fullUrl() }}?type=' + $type, function($data) {
                            $('#form-gallery').html($data);
                            editor().init();
                            Main().init();

                            if ($type === 'longform') {
                                // Initialize longform editor from longform.blade.php
                                if (typeof window.initializeLongformEditor === 'function') {
                                    window.initializeLongformEditor();
                                }
                            }
                        });
                    };


                    $(document).ready(function() {
                        var defaultWidget = $('select[name=type]').val();

                        if ($('select[name=type]').length > 0) {
                            loadFormWidget(defaultWidget);
                            var currentWidget = defaultWidget;

                        } else {
                            defaultWidget = $('meta[name="gallery-type"]');


                            if (defaultWidget.length > 0) {
                                loadFormWidget(defaultWidget.attr('content'));
                                currentWidget = defaultWidget.attr('content');
                            }
                        }

                        $('select[name=type]').change(function(e) {
                            e.preventDefault();
                            const _nextWidget = $(this).val();
                            if (_nextWidget !== currentWidget) {
                                loadFormWidget(_nextWidget);
                                currentWidget = _nextWidget;
                            }
                        });

                        $('[name="category[]"]').on('change', function() {
                            var selectedCategories = $(this).val();

                            if (!selectedCategories || (selectedCategories?.length ?? 0) < 1) {

                                $('[data="base_{{ $language['locale'] }}"]').text(':slug/');


                                return;
                            }

                            $.ajax({
                                url: '{{ route('api.gallery.category.get', ['id' => ':id']) }}'
                                    .replace(':id',
                                        selectedCategories[0]),
                                method: 'GET',
                                success: function(response) {
                                    const catSlug = response?.result?.languages?.find(lang =>
                                        lang
                                        .locale ===
                                        '{{ $language['locale'] }}')?.slug;

                                    $('[data="base_{{ $language['locale'] }}"]').text(catSlug +
                                        '/');

                                }
                            });
                        });


                        // EditorJS validation handling moved to longform.blade.php

                        var handleSubmit = function() {
                            if (currentWidget === 'longform') {
                                // Use the longform submit handler from longform.blade.php
                                if (typeof window.handleLongformSubmit === 'function') {
                                    window.handleLongformSubmit();
                                } else {
                                    $('#save').submit();
                                }
                            } else {
                                $('#save').submit();
                            }
                        }

                        window.handleSubmit = handleSubmit;

                        // expandEditor function moved to longform.blade.php
                    });
                    $('input[name="add-royalty"]').on('change', function() {
                        if ($(this).is(":checked")) $('#royalty-config-wrapper').removeClass('hide');
                        else $('#royalty-config-wrapper').addClass('hide');
                    });
                })(jQuery);
            </script>
        @endpush
