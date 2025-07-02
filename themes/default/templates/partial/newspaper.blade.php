<aside class="paper-news-widget widget">
    <h3 class="widget-title">
        <span class="icon"></span><span>báo in hải quân</span></h3>
    <div class="paper-news-ct">
        @php $featured = get_list_gallery(1,3, true)->first();@endphp
        @if($featured->language('content'))
            @foreach($featured->language('content') as $album)
                @if($loop->last)
                <a href="{{ url('/bao-in-hai-quan') }}"><img src="{{ thumbnail($album['picture'], null, null, 60) }}" alt=""/></a>
                @endif
            @endforeach
        @endif
    </div>
</aside><!-- paper-news -->
