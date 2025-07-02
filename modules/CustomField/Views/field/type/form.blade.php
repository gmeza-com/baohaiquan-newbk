<div class="row">
    <div class="col-lg-offset-3 col-lg-6">
    @component('components.block')
        @slot('title', trans('language.language'))
        <!-- Languages -->

            <div class="form-horizontal form-bordered">
                <ul class="nav nav-tabs" data-toggle="tabs">
                    @foreach(config('cnv.languages') as $language)
                        <li {{ $loop->first ? 'class=active' : '' }}>
                            <a href="#{{ $language['locale'] }}">
                                {{ $language['name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content">
                    @foreach(config('cnv.languages') as $language)
                        <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="{{ $language['locale'] }}">
                            <div class="form-group">
                                {!! Form::label('name', trans('language.name'), ['class' => 'label-control col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('language['. $language['locale'] .'][value]', @$fieldData->language('value', @$language['locale']), [
                                        'class' => 'form-control',
                                        'onchange' => $loop->first ? 'changeSlugField(this);' : ''
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endcomponent

        @component('components.block')
            @slot('title', trans('custom_field::language.information'))
            <div class="form-horizontal form-bordered">

                <div class="form-group">
                    {!! Form::label('position', trans('language.position'), ['class' => 'col-md-3 control-label ']) !!}

                    <div class="col-md-9">
                        {!! Form::number('position', $fieldData->position, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        @endcomponent
    </div>
</div>
@push('footer')
<script>
    var changeSlugField = function (target) {
        var value = target.value;
        var slug = slugify(value);
        $('[name=slug]').val(slug);
    }
</script>
@endpush