@component('components.block')
    @slot('title', 'Link Widget')

    <div class="block-body">
        <div class="text-right">
            <button class="btn btn-success" type="button" onclick="addLinkContentUpdate();">
                <i class="fa fa-plus"></i> {{ trans('language.add') }}
            </button>
            <button class="btn btn-danger" type="button" onclick="removeLinkContentUpdate();">
                <i class="fa fa-minus"></i> {{ trans('language.remove') }}
            </button>
        </div>
        <div class="clearfix"></div>



        <div class="link-container" data-max-key="{{ $widget->content->count() }}">
            @php $i = $widget->content->count() - 1; @endphp
            @foreach ($widget->content->sortByDesc('position') as $content)
                <div class="link-item" data-key="{{ $i }}">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" href="#link_{{ $i }}">
                                    Link #{{ $i + 1 }}
                                    <i class="fa fa-chevron-down pull-right"></i>
                                </a>
                            </h4>
                        </div>
                        <div id="link_{{ $i }}" class="panel-collapse collapse in">
                            <div class="panel-body">
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
                                            <br>
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <div class="choose-thumbnail">
                                                            {!! Form::hidden('content[' . $i . '][language][' . $language['locale'] . '][image]', @$content['language'][$language['locale']]['image'], [
                                                                'id' => 'content_' . $i . '_' . $language['locale'] . '_image',
                                                            ]) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                @if ($loop->first)
                                                <div class="col-lg-8">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                {!! Form::label('position', trans('language.position'), ['class' => 'label-control']) !!}
                                                                {!! Form::number('content[' . $i . '][position]', @$content['position'], [
                                                                    'class' => 'form-control',
                                                                    'required',
                                                                ]) !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <div class="form-group">
                                                                {!! Form::label('url', 'URL', ['class' => 'label-control']) !!}
                                                                {!! Form::url('content[' . $i . '][url]', @$content['url'], [
                                                                    'class' => 'form-control',
                                                                    'required',
                                                                    'placeholder' => 'https://example.com'
                                                                ]) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                {!! Form::label('target', 'Target', ['class' => 'label-control']) !!}
                                                                {!! Form::select('content[' . $i . '][target]', [
                                                                    '_self' => 'Current Tab (_self)',
                                                                    '_blank' => 'New Tab (_blank)'
                                                                ], @$content['target'] ?: '_self', [
                                                                    'class' => 'form-control'
                                                                ]) !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                {!! Form::label('active', 'Active', ['class' => 'label-control']) !!}
                                                                <br>
                                                                <label class="switch switch-primary">
                                                                    <input type="checkbox" name="content[{{ $i }}][active]" value="1"
                                                                        {{ @$content['active'] ? 'checked' : '' }}>
                                                                    <span></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @php $i--; @endphp
            @endforeach
        </div>
        
        <div class="hidden" id="linkTemplate"
            data-template='
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" href="#link___KEY__">
                            Link #__KEY_DISPLAY__
                            <i class="fa fa-chevron-down pull-right"></i>
                        </a>
                    </h4>
                </div>
                <div id="link___KEY__" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <ul class="nav nav-tabs" data-toggle="tabs">
                            @foreach (config('cnv.languages') as $language)
                                <li {{ $loop->first ? 'class=active' : '' }}>
                                    <a href="#{{ $language['locale'] }}___KEY__">
                                        {{ $language['name'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        <div class="tab-content">
                            @foreach (config('cnv.languages') as $language)
                                <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="{{ $language['locale'] }}___KEY__">
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <div class="choose-thumbnail">
                                                    {!! Form::hidden('content[__KEY__][language][' . $language['locale'] . '][image]', null, [
                                                        'id' => 'content___KEY___' . $language['locale'] . '_image',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        </div>
                                        @if ($loop->first)
                                        <div class="col-lg-8">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        {!! Form::label('position', trans('language.position'), ['class' => 'label-control']) !!}
                                                        {!! Form::number('content[__KEY__][position]', '__KEY__', ['class' => 'form-control', 'required']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        {!! Form::label('url', 'URL', ['class' => 'label-control']) !!}
                                                        {!! Form::url('content[__KEY__][url]', null, [
                                                            'class' => 'form-control',
                                                            'required',
                                                            'placeholder' => 'https://example.com'
                                                        ]) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        {!! Form::label('target', 'Target', ['class' => 'label-control']) !!}
                                                        {!! Form::select('content[__KEY__][target]', [
                                                            '_self' => 'Current Tab (_self)',
                                                            '_blank' => 'New Tab (_blank)'
                                                        ], '_self', [
                                                            'class' => 'form-control'
                                                        ]) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        {!! Form::label('active', 'Active', ['class' => 'label-control']) !!}
                                                        <br>
                                                        <label class="switch switch-primary">
                                                            <input type="checkbox" name="content[__KEY__][active]" value="1" checked>
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        '>
    </div>

    <script>
        'use strict';

        var addLinkContentUpdate = function() {
            var content = $('.link-container'),
                key = parseInt(content.data('max-key')),
                template = $('#linkTemplate').data('template');

            template = template.replace(/__KEY__/g, key);
            template = template.replace(/__KEY_DISPLAY__/g, key + 1);
            
            var newItem = $('<div class="link-item" data-key="' + key + '">' + template + '</div>');
            content.prepend(newItem);
            content.data('max-key', key + 1);
            
            Main().init();
            editor().init();
        };

        var removeLinkContentUpdate = function() {
            var content = $('.link-container'),
                key = parseInt(content.data('max-key'));

            key = key > 0 ? key - 1 : 0;
            content.data('max-key', key);
            if (content.children('.link-item').length > 0) {
                content.find('.link-item:first-child').remove();
            }
        }
    </script>
@endcomponent 