@component('components.block')
    @slot('title', trans('gallery::language.post'))
    <div class="block-body">
        <div class="form-bordered">
            <ul class="nav nav-tabs" data-toggle="tabs">
                @foreach (config('cnv.languages') as $language)
                    <li {{ $loop->first ? 'class=active' : '' }}>
                        <a href="#{{ $language['locale'] }}_video">
                            {{ $language['name'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content">
                @foreach (config('cnv.languages') as $language)
                    @php $content = $gallery->language('post_content', $language['locale']) ?: ''; @endphp


                    <div class="form-group">
                        {!! Form::label('description', trans('language.quote_post'), ['class' => 'label-control']) !!}
                        {!! Form::textarea(
                            'language[' . $language['locale'] . '][quote]',
                            @$gallery->language('quote', $language['locale']),
                            ['class' => 'form-control simple_editor'],
                        ) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('description', trans('language.content'), ['class' => 'label-control']) !!}
                        {!! Form::textarea('language[' . $language['locale'] . '][post_content]', $content, [
                            'class' => 'form-control editor',
                            'required',
                        ]) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('description', trans('language.note_post'), ['class' => 'label-control']) !!}
                        {!! Form::textarea(
                            'language[' . $language['locale'] . '][note]',
                            @$gallery->language('note', $language['locale']),
                            ['class' => 'form-control simple_editor'],
                        ) !!}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endcomponent
