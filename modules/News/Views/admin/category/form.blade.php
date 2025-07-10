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
                                @if ($loop->first)
                                    <div class="form-group">
                                        {!! Form::label('parent_id', trans('news::language.parent_category'), ['class' => 'label-control']) !!}
                                        {!! Form::select(
                                            'parent_id',
                                            $category->getParentForSelection($language['locale'], true, true),
                                            @$category->parent_id,
                                            ['class' => 'form-control', 'required'],
                                        ) !!}
                                    </div>
                                @endif
                                @if (config('cnv.seo_plugin'))
                                    <div class="form-group">
                                        {!! Form::label('description', trans('language.description'), ['class' => 'label-control']) !!}
                                        {!! Form::textarea(
                                            'language[' . $language['locale'] . '][description]',
                                            $category->seo ? @$category->seo->language('description', $language['locale']) : null,
                                            ['class' => 'form-control', 'required'],
                                        ) !!}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endcomponent
        @include('seo_plugin::form', ['base' => 'danh-muc', 'model' => $category])
    </div>
    <div class="col-lg-4">
        @component('components.block')
            @slot('title', trans('language.setting_field'))
            <div class="block-body">
                <div class="form-horizontal form-bordered">
                    <div class="form-group" style="padding: 1px; border-width: 0">
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
            @slot('title', trans('language.thumbnail'))
            <div class="block-body">
                <div class="form_group">
                    <div class="choose-thumbnail">
                        {!! Form::hidden('thumbnail', $category->thumbnail, ['id' => 'thumbnail']) !!}
                    </div>
                </div>
            </div>
        @endcomponent
    </div>
</div>

@include('partial.editor')
