<div class="owl-carousel owl-theme">
    @foreach($widget->content->sortBy('position') as $content)
        <div class="item">
            <a href="{{ @$content['link'] ?: '#'}}" title="{{ @$content['language'][$current_language]['title'] }}">
                <img src="{{ @$content['language'][$current_language]['picture'] }}" alt="{{ @$content['language'][$current_language]['title'] }}">
            </a>
            <h4>{{ @$content['language'][$current_language]['title'] }}</h4>
            @if(@$content['language'][$current_language]['description'])
                <p>{{ @$content['language'][$current_language]['description'] }}</p>
            @endif
        </div>
    @endforeach
</div>