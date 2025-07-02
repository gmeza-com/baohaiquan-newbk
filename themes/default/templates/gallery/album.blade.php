@extends('theme::layout')

@section('content')

    <h1>{{ $gallery->name }}</h1>
    <div class="meta">
        <span class="author">
            {!! trans('gallery::web.published_by', ['author' => $gallery->gallery->author->username]) !!}
        </span>
        <span class="updated_at">
            {!! trans('gallery::web.published_at', ['datetime' => $gallery->gallery->published_at])  !!}
        </span>
        <span class="views">
            {!! trans('gallery::web.view_count', ['count' => $gallery->gallery->view->count]) !!}
        </span>
        <span>{!! implode(', ', $gallery->gallery->list_categories) !!}</span>
    </div>
    <div class="content">
        @foreach($gallery->content as $content)
            <a href="{{ @$content['link'] ?: '#' }}">
                <img src="{{ @$content['picture'] }}" alt="{{ @$content['title'] }}">
                <h4>{{ @$content['title'] }}</h4>
                {{ @$content['description'] }}
            </a>
        @endforeach
    </div>
@endsection