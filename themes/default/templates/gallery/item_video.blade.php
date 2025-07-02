<article>
    <h2 class="text-center video-title test3">
        <a href="{{ $item->language('link') }}">{{ $item->language('name') }}</a>
    </h2>
    <div class="post-meta">
                <span class="author">
                    {!! trans('gallery::web.published_by', ['author' => $item->author->username]) !!}
                </span> &bull;
        <span class="updated_at">
                    {!! trans('gallery::web.published_at', ['datetime' => $item->published_at])  !!}
                </span> &bull;
        <span class="views">
                    {!! trans('gallery::web.view_count', ['count' => $item->view->count]) !!}
                </span>

        <span>{!! implode(', ', $item->list_categories) !!}</span>

    </div>
    <div class="post-thumbnail">
        <img src="{{ $item->language('thumbnail') }}" alt="">
    </div>
    <div class="post-description">
        {{ $item->language('description') }}
    </div>
</article>
