@component('components.block')
    @slot('title', 'Video')
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
                    @php $content = $gallery->language('content', $language['locale']) ?: collect([]); @endphp
                    <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="{{ $language['locale'] }}_video">
                        <div class="form-group">
                            {!! Form::label('link', trans('language.link'), ['class' => 'label-control']) !!}

                            <div class="input-group">
                                {!! Form::text('language[' . $language['locale'] . '][content][link]', @$content['link'], [
                                    'class' => 'form-control',
                                    'required',
                                    'id' => 'map-icon',
                                ]) !!}
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-primary" data-toggle="files"
                                        data-target="map-icon"><i class="fa fa-video-camera"></i></button>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('content', trans('language.content'), ['class' => 'label-control']) !!}
                            {!! Form::textarea('language[' . $language['locale'] . '][content][content]', @$content['content'], [
                                'class' => 'form-control simple_editor',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endcomponent
