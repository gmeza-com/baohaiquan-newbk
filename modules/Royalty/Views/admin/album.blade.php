@component('components.block')
    @slot('title', 'Album')
    <div class="block-body">
        <div class="form-bordered">
            <ul class="nav nav-tabs" data-toggle="tabs">
                @foreach (config('cnv.languages') as $language)
                    <li {{ $loop->first ? 'class=active' : '' }}>
                        <a href="#{{ $language['locale'] }}_album">
                            {{ $language['name'] }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content">
                @foreach (config('cnv.languages') as $language)
                    @php $content = $gallery->language('content', $language['locale']) ?: collect([]); @endphp
                    <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="{{ $language['locale'] }}_album">
                        <div class="text-right" style="padding: 6px">
                            <button class="btn btn-success" type="button"
                                onclick="addLineContent('{{ $language['locale'] }}');">
                                <i class="fa fa-plus"></i>
                            </button>
                            <button class="btn btn-danger" type="button"
                                onclick="removeLineContent('{{ $language['locale'] }}');">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>

                        <div class="{{ $language['locale'] }}-gallery-container" data-max-key="{{ $content->count() }}">
                            @php $i = $content->count() - 1; @endphp
                            @foreach ($content->sortByDesc('position') as $item)
                                <div class="gallery-item">
                                    <br><br>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form_group">
                                                <div class="choose-thumbnail">
                                                    {!! Form::hidden('language[' . $language['locale'] . '][content][' . $i . '][picture]', @$item['picture'], [
                                                        'id' => 'content_' . $i . '_' . $language['locale'],
                                                    ]) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="form-group">
                                                {!! Form::label('description', trans('language.title'), ['class' => 'label-control']) !!}
                                                {!! Form::text('language[' . $language['locale'] . '][content][' . $i . '][title]', @$item['title'], [
                                                    'class' => 'form-control',
                                                    'required',
                                                ]) !!}
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label('description', trans('language.description'), ['class' => 'label-control']) !!}
                                                {!! Form::textarea(
                                                    'language[' . $language['locale'] . '][content][' . $i . '][description]',
                                                    @$item['description'],
                                                    ['class' => 'form-control', 'rows' => 5],
                                                ) !!}
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('position', trans('language.position'), ['class' => 'label-control']) !!}
                                                        {!! Form::number('language[' . $language['locale'] . '][content][' . $i . '][position]', @$item['position'], [
                                                            'class' => 'form-control',
                                                            'required',
                                                        ]) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        {!! Form::label('link', trans('language.link'), ['class' => 'label-control']) !!}
                                                        {!! Form::text('language[' . $language['locale'] . '][content][' . $i . '][link]', @$item['link'], [
                                                            'class' => 'form-control',
                                                        ]) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                                @php $i--; @endphp
                            @endforeach
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endcomponent
<div class="hidden" id="lineTemplate"
    data-template='
    <div class="gallery-item">
    <br><br>
    <div class="row">
        <div class="col-lg-4">
            <div class="form_group">
                <div class="choose-thumbnail">
                    {!! Form::hidden('language[__LANG__][content][__KEY__][picture]', null, [
                        'id' => 'content_' . $i . '_' . $language['locale'],
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="form-group">
                {!! Form::label('description', trans('language.title'), ['class' => 'label-control']) !!}
                {!! Form::text('language[__LANG__][content][__KEY__][title]', null, ['class' => 'form-control', 'required']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('description', trans('language.description'), ['class' => 'label-control']) !!}
                {!! Form::textarea('language[__LANG__][content][__KEY__][description]', null, [
                    'class' => 'form-control',
                    'rows' => 5,
                ]) !!}
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('position', trans('language.position'), ['class' => 'label-control']) !!}
                        {!! Form::number('language[__LANG__][content][__KEY__][position]', '__KEY__', [
                            'class' => 'form-control',
                            'required',
                        ]) !!}
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        {!! Form::label('link', trans('language.link'), ['class' => 'label-control']) !!}
                        {!! Form::text('language[__LANG__][content][__KEY__][link]', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
</div>
'>
</div>

<script>
    'use strict';

    var addLineContent = function($language) {
        var content = $('.' + $language + '-gallery-container'),
            key = parseInt(content.data('max-key')),
            template = $('#lineTemplate').data('template');

        template = template.replace(/__KEY__/g, key);
        template = template.replace(/__LANG__/g, $language);
        content.prepend(template);
        content.data('max-key', key + 1);
        Main().init();
        editor().init();
    };

    var removeLineContent = function($language) {
        var content = $('.' + $language + '-gallery-container'),
            key = parseInt(content.data('max-key'));

        key = key > 0 ? key - 1 : 0;
        content.data('max-key', key);
        if (content.children('.gallery-item').length > 0) {
            content.find('.gallery-item:first-child').remove();
        }
    }
</script>
