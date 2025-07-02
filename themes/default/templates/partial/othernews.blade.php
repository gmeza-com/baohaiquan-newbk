<div class="other_news">
    <div class="otherpost_title">Tin tức khác</div>
    @php
    $latestPosts = get_list_news_posts(5, $category_id);
    @endphp
    <ul class="otherpost_item">
        @foreach($latestPosts as $lastest)
        <li>
            <a href="{{ $lastest->language('link') }}"><i class="fa fa-chevron-right" aria-hidden="true"></i>{{ $lastest->language('name') }} - ( {{ $lastest->published_at->format('d-m-y h:s') }} )</a>
        </li>
        @endforeach
    </ul>
</div>