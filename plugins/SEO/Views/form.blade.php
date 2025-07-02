@if(config('cnv.seo_plugin'))
    @component('components.block')
        @slot('title', trans('seo_plugin::language.seo'))
            <div class="text-right">
                <button type="button" id="showEditSearchEngineOptimized" class="btn btn-xs btn-info">
                    <i class="fa fa-pencil-square-o"></i> {{ trans('language.edit') }}
                </button>
            </div>

            <div class="form-horizontal form-bordered">
                <div id="display-seo" class="seo_plugin">
                    <ul class="nav nav-tabs" data-toggle="tabs">
                        @foreach(config('cnv.languages') as $language)
                            <li {{ $loop->first ? 'class=active' : '' }}>
                                <a href="#seo-{{ $language['locale'] }}">
                                    {{ $language['name'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content">
                        @foreach(config('cnv.languages') as $language)
                            <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="seo-{{ $language['locale'] }}">
                                <h4 class="seo_plugin_title" data="title_{{ $language['locale'] }}">{{ @$model->seo ? @$model->seo->language('title', $language['locale']) : 'This is sample url' }}</h4>
                                <div class="seo_plugin_url">
                                    {{ get_option('site_url') }}/{{ $base ? $base . '/' : '' }}<strong data="url_{{ $language['locale'] }}">{{ @$model->language('slug', $language['locale']) ?: 'sample-url' }}</strong>
                                </div>
                                <div class="seo_plugin_description" data="description_{{ $language['locale'] }}">
                                    {{ @$model->seo ? @$model->seo->language('description', $language['locale']) : '
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. In enim ligula, euismod vel pulvinar rhoncus, varius ut lacus. Aenean et nulla nunc. Maecenas volutpat.
                                    ' }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div id="form-seo" class="hidden">
                    <ul class="nav nav-tabs" data-toggle="tabs">
                        @foreach(config('cnv.languages') as $language)
                            <li {{ $loop->first ? 'class=active' : '' }}>
                                <a href="#seo-edit-{{ $language['locale'] }}">
                                    {{ $language['name'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content">
                        @foreach(config('cnv.languages') as $language)
                            <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="seo-edit-{{ $language['locale'] }}">
                                <div class="form-group">
                                    {!! Form::label('title', trans('seo_plugin::language.title'), ['class' => 'label-control col-md-3']) !!}
                                    <div class="col-md-9">
                                        {!! Form::text('seo[language]['. $language['locale'] .'][title]', @$model->seo ? @$model->seo->language('title', $language['locale']) : null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                        <p class="help-block"><span data="count">0</span>/70</p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('description', trans('seo_plugin::language.description'), ['class' => 'label-control col-md-3']) !!}
                                    <div class="col-md-9">
                                        {!! Form::textarea('seo[language]['. $language['locale'] .'][description]', @$model->seo ? @$model->seo->language('description', $language['locale']) : null, ['class' => 'form-control', 'maxlength' => 160]) !!}
                                        <p class="help-block"><span data="count">0</span>/160</p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('slug', trans('seo_plugin::language.seo_url'), ['class' => 'label-control col-md-3']) !!}
                                    <div class="col-md-9">
                                        {!! Form::text('language['. $language['locale'] .'][slug]', @$model->language('slug', $language['locale']), ['class' => 'form-control', 'required']) !!}
                                        <p class="help-block">{{ get_option('site_url') }}/{{ $base ? $base . '/' : '' }}<strong data="url_{{ $language['locale'] }}">{{ @$model->language('slug', $language['locale']) ?: 'sample-url' }}</strong></p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
    @endcomponent

    @push('header')
            <style>
                .seo_plugin {
                    padding: 10px;
                }

                .seo_plugin_title {
                    min-height: 21px;
                    display: block;
                    font-size: 18px;
                    color: #1a0dab;
                    line-height: 21px;
                    margin-bottom: 2px;
                }



                .seo_plugin_url {
                    display: block;
                    word-wrap: break-word;
                    color: #006621;
                    font-size: 13px;
                    line-height: 16px;
                    margin-bottom: 2px;
                }

                .seo_plugin_description {
                    display: block;
                    color: #545454;
                    line-height: 18px;
                    font-size: 13px;
                }
            </style>
    @endpush

    @push('footer')
            <script>
                'use strict';

               (function($) {
                    $('#showEditSearchEngineOptimized').on('click', function(e) {
                        e.preventDefault();
                        $('#form-seo').removeClass('hidden');
                        $('#display-seo').remove();
                        $(this).remove();
                    });
                   @foreach(config('cnv.languages') as $language)
                   $('[name="language[{{ $language['locale'] }}][name]"]').keyup(function() {
                       $('[data="title_{{ $language['locale'] }}"]').html($(this).val().substr(0, 69));
                       $('[name="seo[language][{{ $language['locale'] }}][title]"]').val($(this).val().substr(0,69));
                       $('[data="url_{{ $language['locale'] }}"]').html(slugify($(this).val()));
                       $('[name="language[{{ $language['locale'] }}][slug]"]').val(slugify($(this).val()));
                   });
                   $('[name="language[{{ $language['locale'] }}][description]"]').keyup(function() {
                       $('[data="description_{{ $language['locale'] }}"]').html($(this).val().substr(0,169));
                       $('[name="seo[language][{{ $language['locale'] }}][description]"]').val($(this).val().substr(0,169));
                   });
                   $('[name="language[{{ $language['locale'] }}][slug]"]').keyup(function() {
                       $('[data="url_{{ $language['locale'] }}"]').html(slugify($(this).val()));
                   });
                   $('[name="seo[language][{{ $language['locale'] }}][title]"]').keyup(function() {
                       var target = $(this).next().find('[data=count]');
                           target.text($(this).val().length);
                           $('[name="language[{{ $language['locale'] }}][slug]"]').val(slugify($(this).val()));
                           $('[data="url_{{ $language['locale'] }}"]').html(slugify($(this).val()));
                   });
                   $('[name="seo[language][{{ $language['locale'] }}][description]"]').keyup(function() {
                       var target = $(this).next().find('[data=count]');
                       target.text($(this).val().length);
                   });
                   @endforeach
                })(jQuery);
            </script>
    @endpush
@endif