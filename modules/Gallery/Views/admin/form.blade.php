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
                                {!! Form::select('type', ['album' => 'Album', 'video' => 'Video'], null, ['class' => 'form-control']) !!}
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
            </>
        </div>

        @include('partial.editor')

        @push('footer')
            <script>
                "use strict";
                (function($) {
                    var loadFormWidget = function($type) {
                        $.get('{{ request()->fullUrl() }}?type=' + $type, function($data) {
                            $('#form-gallery').html($data);
                            editor().init();
                            Main().init();
                        });
                    };

                    $(document).ready(function() {
                        var defaultWidget = $('select[name=type]').val();
                        if ($('select[name=type]').length > 0) {
                            loadFormWidget(defaultWidget);
                        } else {
                            defaultWidget = $('meta[name="gallery-type"]');
                            if (defaultWidget.length > 0) {
                                loadFormWidget(defaultWidget.attr('content'));
                            }
                        }

                        $('select[name=type]').change(function(e) {
                            e.preventDefault();
                            loadFormWidget($(this).val());
                        });
                    });


                    $('[name="category[]"]').on('change', function() {
                        var selectedCategories = $(this).val();

                        if (!selectedCategories || (selectedCategories?.length ?? 0) < 1) {

                            $('[data="base_{{ $language['locale'] }}"]').text(':slug/');


                            return;
                        }

                        $.ajax({
                            url: '{{ route('api.gallery.category.get', ['id' => ':id']) }}'.replace(':id',
                                selectedCategories[0]),
                            method: 'GET',
                            success: function(response) {
                                const catSlug = response?.result?.languages?.find(lang => lang.locale ===
                                    '{{ $language['locale'] }}')?.slug;

                                $('[data="base_{{ $language['locale'] }}"]').text(catSlug + '/');

                            }
                        });
                    });
                })(jQuery);
            </script>
        @endpush
