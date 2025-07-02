@extends('theme::layout')

@section('content')
<section id="include">
    <div class="container">
        <div class="row ">
            <div class="col-sm-8">
                @include('theme::partial.breadcum')
                @foreach($posts as $post)
                <div class="wrap-news-list destop-row" data-wow-delay="0.1s">
                    <div class="col-sm-5 img-news">
                        <div class="img-ctxh">
                                <a href="{{ $post->link }}" title="{{ $post->name }}">
                                    <div class="mask">
                                        <div>
                                            <img src="{{ $theme_url }}/images/assets/link.png">
                                        </div>
                                    </div>
                                        <img src="{{ $post->post->thumbnail }}">
                                    <div class="info-time">
                                        <p>{{ $post->post->published_at->format('d') }}</p>
                                        <p>T{{ $post->post->published_at->format('m') }}</p>
                                    </div>
                                </alt="chính></a>
                            </div>
                    </div>
                    <div class="col-sm-7 quote-news destop-pr0">
                        <h3><a href="{{ $post->link }}">{{ $post->name }}</a></h3>
                        <p>{{ $post->description }}</p>
                    </div>
                    <div class="clearfix"></div>
                </div>
                @endforeach
                <div class="col-sm-12 text-center">
                    {!! $posts->links() !!}
                </div>
            </div>
            <div class="col-sm-4 navleft destop-pr0 tablet-pr0">
                @include('theme::partial.slidebar')
            </div>
        </div>
    </div>
</section>
@endsection
