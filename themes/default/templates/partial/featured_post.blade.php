<aside class="featured-news-widget widget">
    <h3 class="widget-title bg"><span class="widget-title-fix">Tin nổi bật</span></h3>

    <div class="featured-news-sliders">
        <ul class="bxslider">
            @foreach(get_list_news_posts(5, 29, true) as $featured)
            <li>
                <a class="thumb" href="{{ $featured->language('link') }}">
                    <img src="{{ thumbnail($featured->thumbnail, null, null, 60) }}" alt="">
                </a>
                <div class="info">
                    <h4><a href="{{ $featured->language('link') }}">{{ $featured->language('name') }}</a></h4>
                    {{-- <span class="date"><i class="fa fa-clock-o"></i>{{ $featured->published_at->format('d-m-y') }}</span> --}}
                    {{-- <a class="cat" href="{{ $featured->language('link') }}">{!! implode(', ', $featured->list_categories) !!}</a> --}}
                </div>
            </li>
            @endforeach
        </ul>
    </div><!-- .featured-news-slider -->
</aside>
