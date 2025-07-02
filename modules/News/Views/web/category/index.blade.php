@extends('theme::layout')

@section('content')

    <h1>{{ $category->name }}</h1>

    <h2>Nổi bật</h2>
    @foreach($featured_posts as $post)
        <article>
            <h2>
                <a href="{{ $post->language('link') }}">{{ $post->language('name') }}</a>
            </h2>
            <div class="post-meta">
                <span class="author">
                    {!! trans('news::web.published_by', ['author' => $post->author->username]) !!}
                </span> &bull;
                <span class="updated_at">
                    {!! trans('news::web.published_at', ['datetime' => $post->published_at])  !!}
                </span> &bull;
                <span class="categories">
                    {!! trans('news::web.in_categories', ['categories' => implode(', ', $post->list_categories)]) !!}
                </span> &bull;
                <span class="views">
                    {!! trans('news::web.view_count', ['count' => $post->view->count]) !!}
                </span>
            </div>
            <div class="post-description">
                {{ $post->language('description') }}
            </div>
        </article>
    @endforeach

    <h2>Mới nhất</h2>
    @foreach($posts as $post)
        <article>
            <h2>
                <a href="{{ $post->language('link') }}">{{ $post->language('name') }}</a>
            </h2>
            <div class="post-meta">
                <span class="author">
                    {!! trans('news::web.published_by', ['author' => $post->author->username]) !!}
                </span> &bull;
                <span class="updated_at">
                    {!! trans('news::web.published_at', ['datetime' => $post->published_at])  !!}
                </span> &bull;
                <span class="categories">
                    {!! trans('news::web.in_categories', ['categories' => implode(', ', $post->list_categories)]) !!}
                </span> &bull;
                <span class="views">
                    {!! trans('news::web.view_count', ['count' => $post->view->count]) !!}
                </span>
            </div>
            <div class="post-description">
                {{ $post->language('description') }}
            </div>
        </article>
    @endforeach

    <div class="pagination">
        {!! $posts->links() !!}
    </div>
@endsection
