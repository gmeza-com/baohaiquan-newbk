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
                                    {!! Form::text(
                                        'language[' . $language['locale'] . '][name]',
                                        @$category->language('name', $language['locale']),
                                        ['class' => 'form-control', 'required'],
                                    ) !!}
                                </div>
                                @if (config('cnv.seo_plugin'))
                                    <div class="form-group">
                                        {!! Form::label('description', trans('language.description'), ['class' => 'label-control']) !!}
                                        <textarea name="language[{{ $language['locale'] }}][description]" id="description" class="form-control" rows="10"
                                            required>{{ $category->seo ? @$category->seo->language('description', $language['locale']) : null }}</textarea>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endcomponent
        @include('seo_plugin::form', ['base' => 'podcast-category', 'model' => $category])
    </div>
    <div class="col-lg-4">
        @component('components.block')
            @slot('title', trans('language.setting_field'))
            <div class="block-body">
                <div class="form-horizontal form-bordered">
                    <div class="form-group">
                        {!! Form::label('published', trans('language.published'), ['class' => 'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            <label class="switch switch-primary">
                                <input type="checkbox" name="published" value="1"
                                    {{ @$category->published ? 'checked' : '' }}>
                                <span></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        @endcomponent

        @component('components.block')
            @slot('title', 'Icon')
            <div class="block-body">
                <div class="form_group">
                    <div class="choose-thumbnail">
                        {!! Form::hidden('icon', $category->icon, ['id' => 'icon']) !!}
                    </div>
                </div>
            </div>
        @endcomponent
    </div>
</div>

@include('partial.editor')
