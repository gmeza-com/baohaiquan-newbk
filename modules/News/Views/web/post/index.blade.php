@extends('theme::layout')

@section('content')

    <h1>{{ $post->name }}</h1>
    <div class="meta">
        <span class="author">
            {!! trans('news::web.published_by', ['author' => $post->post->author->username]) !!}
        </span>
        <span class="updated_at">
            {!! trans('news::web.published_at', ['datetime' => $post->post->published_at])  !!}
        </span>
        <span class="categories">
            {!! trans('news::web.in_categories', ['categories' => implode(', ', $post->post->list_categories)]) !!}
        </span>
    </div>
    <div class="content">
        {!! $post->content !!}
    </div>
@endsection
