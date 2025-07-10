@component('components.block')
    @slot('title', 'Slider')

    <div class="block-body">
        <div class="text-right">
            <button class="btn btn-success" type="button" onclick="addLineContentUpdate();">
                <i class="fa fa-plus"></i>
            </button>
            <button class="btn btn-danger" type="button" onclick="removeLineContentUpdate();">
                <i class="fa fa-minus"></i>
            </button>
        </div>
        <div class="clearfix"></div>

        <div class="form-group">
            {!! Form::select('setting', (new \Modules\Slider\Widgets\Slider())->getSliderTemplates(), $widget->setting, [
                'class' => 'form-control',
            ]) !!}
        </div>

        <div class="slider-container" data-max-key="{{ $widget->content->count() }}">
            @php $i = $widget->content->count() - 1; @endphp
            @foreach ($widget->content->sortByDesc('position') as $content)
                <div class="slider-item" data-key="{{ $i }}">
                    <ul class="nav nav-tabs" data-toggle="tabs">
                        @foreach (config('cnv.languages') as $language)
                            <li {{ $loop->first ? 'class=active' : '' }}>
                                <a href="#{{ $language['locale'] }}_{{ $i }}">
                                    {{ $language['name'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content">
                        @foreach (config('cnv.languages') as $language)
                            <div class="tab-pane {{ $loop->first ? 'active' : '' }}"
                                id="{{ $language['locale'] }}_{{ $i }}">
                                <br><br>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form_group">
                                            <div class="choose-thumbnail">
                                                {!! Form::hidden(
                                                    'content[' . $i . '][language][' . $language['locale'] . '][picture]',
                                                    @$content['language'][$language['locale']]['picture'],
                                                    ['id' => 'content_' . $i . '_' . $language['locale']],
                                                ) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            {!! Form::label('description', trans('language.title'), ['class' => 'label-control']) !!}
                                            {!! Form::text(
                                                'content[' . $i . '][language][' . $language['locale'] . '][title]',
                                                @$content['language'][$language['locale']]['title'],
                                                ['class' => 'form-control', 'required'],
                                            ) !!}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('description', trans('language.description'), ['class' => 'label-control']) !!}
                                            {!! Form::textarea(
                                                'content[' . $i . '][language][' . $language['locale'] . '][description]',
                                                @$content['language'][$language['locale']]['description'],
                                                ['class' => 'form-control', 'rows' => 5],
                                            ) !!}
                                        </div>
                                        @if ($loop->first)
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        {!! Form::label('position', trans('language.position'), ['class' => 'label-control']) !!}
                                                        {!! Form::number('content[' . $i . '][position]', @$content['position'], [
                                                            'class' => 'form-control',
                                                            'required',
                                                        ]) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        {!! Form::label('link', trans('language.link'), ['class' => 'label-control']) !!}
                                                        {!! Form::text('content[' . $i . '][link]', @$content['link'], ['class' => 'form-control']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <hr>
                </div>
                @php $i--; @endphp
            @endforeach
        </div>
        <div class="hidden" id="lineTemplate"
            data-template='
        <div class="slider-item" data-key="__KEY__">
            <ul class="nav nav-tabs" data-toggle="tabs">
                @foreach (config('cnv.languages')
            as $language) <li {{ $loop->first ? 'class=active' : '' }}>
            <a href="#{{ $language['locale'] }}___KEY__">
                {{ $language['name'] }}
            </a>
            </li>
            @endforeach
            </ul>

            <div class="tab-content">
                @foreach (config('cnv.languages') as $language)
                    <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="{{ $language['locale'] }}___KEY__">
                        <br><br>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form_group">
                                    <div class="choose-thumbnail">
                                        {!! Form::hidden('content[__KEY__][language][' . $language['locale'] . '][picture]', null, [
                                            'id' => 'content_' . $i . '_' . $language['locale'],
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    {!! Form::label('description', trans('language.title'), ['class' => 'label-control']) !!}
                                    {!! Form::text('content[__KEY__][language][' . $language['locale'] . '][title]', null, [
                                        'class' => 'form-control',
                                        'required',
                                    ]) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('description', trans('language.description'), ['class' => 'label-control']) !!}
                                    {!! Form::textarea('content[__KEY__][language][' . $language['locale'] . '][description]', null, [
                                        'class' => 'form-control',
                                        'rows' => 5,
                                    ]) !!}
                                </div>
                                @if ($loop->first)
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                {!! Form::label('position', trans('language.position'), ['class' => 'label-control']) !!}
                                                {!! Form::number('content[__KEY__][position]', '__KEY__', ['class' => 'form-control', 'required']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                {!! Form::label('link', trans('language.link'), ['class' => 'label-control']) !!}
                                                {!! Form::text('content[__KEY__][link]', null, ['class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <hr>
        </div>
        '>
    </div>

    </div>
    <script>
        'use strict';

        var addLineContentUpdate = function() {
            var content = $('.slider-container'),
                key = parseInt(content.data('max-key')),
                template = $('#lineTemplate').data('template');

            template = template.replace(/__KEY__/g, key);
            content.prepend(template);
            content.data('max-key', key + 1);
            Main().init();
            editor().init();
        };

        var removeLineContentUpdate = function() {
            var content = $('.slider-container'),
                key = parseInt(content.data('max-key'));

            key = key > 0 ? key - 1 : 0;
            content.data('max-key', key);
            if (content.children('.slider-item').length > 0) {
                content.find('.slider-item:first-child').remove();
            }
        }
    </script>
@endcomponent
