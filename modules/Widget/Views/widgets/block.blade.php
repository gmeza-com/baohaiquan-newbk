@component('components.block')
    @slot('title', 'Block')

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
                            {!! Form::label('description', trans('language.content'), ['class' => 'label-control']) !!}
                            {!! Form::textarea('content[' . $language['locale'] . ']', @$widget[$language['locale']], [
                                'class' => 'form-control editor',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
@endcomponent
