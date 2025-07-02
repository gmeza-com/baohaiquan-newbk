@if(count(config('cnv.languages')) > 1)
    @component('components.block')
        <div>
            @php $lang = Request::has('language') ? Request::get('language') : config('cnv.language_default'); @endphp
            @foreach(config('cnv.languages') as $language)
                <a href="{{ $url }}?language={{ $language['locale'] }}" class="btn btn-sm btn-alt
                    {{ $language['locale'] == $lang ? 'btn-primary' : 'btn-default' }}">
                    <i class="flag-icon flag-icon-{{ $language['flag'] }}"></i> {{ $language['name'] }}
                </a>
            @endforeach
        </div>
    @endcomponent
@endif