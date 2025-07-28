<div class="row">
    <div class="col-lg-offset-3 col-lg-6">
        @component('components.block')
            @slot('title', trans('language.language'))
            <!-- Languages -->

            <div class="block-body">
                <div class="form-horizontal form-bordered">
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
                                    {!! Form::label('name', trans('custom_field::language.name'), ['class' => 'label-control col-md-3']) !!}
                                    <div class="col-md-9">
                                        {!! Form::text('language[' . $language['locale'] . '][name]', @$field->language('name', @$language['locale']), [
                                            'class' => 'form-control',
                                            'onchange' => $loop->first ? 'changeSlugField(this);' : '',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endcomponent

        @component('components.block')
            @slot('title', trans('custom_field::language.information'))
            <div class="form-horizontal form-bordered">

                <div class="form-group">
                    {!! Form::label('type', trans('custom_field::language.type'), ['class' => 'col-md-3 control-label ']) !!}

                    <div class="col-md-9">
                        @if (!$field->id)
                            {!! Form::select('type', $type, null, ['class' => 'form-control', 'required']) !!}
                        @else
                            <div class="form-control-static">{{ ucfirst($field->type) }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('module', trans('custom_field::language.module'), ['class' => 'col-md-3 control-label']) !!}

                    <div class="col-md-9">
                        @if (!$field->id)
                            <select name="module_name" id="module_name" class="form-control" required>
                                <option value="" selected disabled>Chọn Modules</option>
                                @foreach ($modules as $slug => $name)
                                    <option value="{{ $slug }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        @else
                            <div class="form-control-static">{{ ucfirst($field->module) }}</div>
                        @endif
                    </div>
                </div>
                @if (!$field->id)
                    {!! Form::hidden('slug', null) !!}
                @endif

                <div class="form-group">
                    {!! Form::label('hidden', trans('custom_field::language.hide'), ['class' => 'control-label col-md-3']) !!}
                    <div class="col-md-9">
                        <label class="switch switch-warning">
                            <input type="checkbox" name="hidden" value="1" {{ @$field->hidden ? 'checked' : '' }}>
                            <span></span>
                        </label>
                    </div>
                </div>
            </div>
        @endcomponent
    </div>
</div>
@push('footer')
    <script>
        var changeSlugField = function(target) {
            var value = target.value;
            var slug = slugify(value);
            $('[name=slug]').val(slug);
        }
    </script>
@endpush
