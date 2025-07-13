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
        {!! @$gallery->content['content'] !!}

        @if(\App\Libraries\Str::isYoutubeLink(@$gallery->content['link']))
            <iframe width="560" height="315" src="{{ \App\Libraries\Str::parseYoutubeLinkToEmbed(@$gallery->content['link']) }}" frameborder="0" allowfullscreen></iframe>
        @else
            @push('header')
                <link href="http://vjs.zencdn.net/5.19.1/video-js.css" rel="stylesheet">
                <!-- If you'd like to support IE8 -->
                <script src="http://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
            @endpush
            <video id="my-video" class="video-js" controls preload="auto" width="640" height="264" data-setup="{}">
                <source src="{{ @$gallery->content['link'] }}" type='video/mp4'>
                <p class="vjs-no-js">
                    To view this video please enable JavaScript, and consider upgrading to a web browser that
                    <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                </p>
            </video>

            @push('footer')
                <script src="http://vjs.zencdn.net/5.19.1/video.js"></script>
            @endpush
        @endif
    </div>
@endsection