@component('components.block')
    @slot('title', 'Longform')
    <div class="block-body">
        <div class="form-bordered">
            <ul class="nav nav-tabs" data-toggle="tabs">
                @foreach (config('cnv.languages') as $language)
                    <li {{ $loop->first ? 'class=active' : '' }}>
                        <a href="#{{ $language['locale'] }}_longform">
                            {{ $language['name'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content">
                @foreach (config('cnv.languages') as $language)
                    @php $content = $gallery->language('content', $language['locale']) ?: collect([]); @endphp
                    <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="{{ $language['locale'] }}_longform">
                        <div class="longform-container ignore-validation">
                            <div class="longform-nav">

                            </div>

                            <textarea id="editor-content-{{ $language['locale'] }}" name="language[{{ $language['locale'] }}][content]" hidden></textarea>
                            <div id="longform-content-{{ $language['locale'] }}" class="longform-content"></div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endcomponent
