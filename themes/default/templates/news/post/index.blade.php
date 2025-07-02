@extends('theme::layout')
@push('header')
    <link rel="stylesheet" type="text/css" href="{{ $theme_url }}/css/social-likes_birman.css" />
    <script>
        CNV.categoryActive = '/danh-muc?id=' + {{ $post->post->categories->first()->id }};
    </script>

    @include('seo_plugin::seo', [
        'type' => 'article',
        'title' => $title,
        'description' => $description,
        'image' => url(@$post->post->thumbnail ? $post->post->thumbnail : 'unknown'),
        'published_time' => $post->post->published_at
    ])
<meta property="og:image:width" content="600" />
<meta property="og:image:height" content="400" />
@endpush
@push('footer')
    <script src='{{ $theme_url }}/js/social-likes.js' type='text/javascript'></script>
@endpush
@section('content')
<section id="include">
    <div class="container">
        <div class="row"> 
            <div class="col-sm-{{ in_array(32, $post->post->categories->map->id->toArray()) ? '12' : '8' }}">
                @include('theme::partial.breadcum')

                <h1 class=" title-news-detail-left ">{{ $post->name }}</h1>
                @if($post->second_name)
                <h2 class="title-news-detail">{{ $post->second_name }}</h2>
                @endif
                @if($post->third_name)
                <h3 class="title-news-detail">{{ $post->third_name }}</h2>
                @endif

                <div class="que_news">
                    {!! $post->quote ? ($post->post->prefix ? '<p class="hqol">' . $post->post->prefix . ' - </p>' : '') . $post->quote : '' !!}
                </div>
                <div class="content_news">
                    {!! $post->content !!}

                    @if($post->note)
                    <blockquote>
                        {!! $post->note !!}
                    </blockquote>
                    @endif
                </div>
                <div class="clearfix"></div>
                <div class="messenger_single">
                    {!! widget('dong-gop-bai-viet') !!}
                </div>
                <div class="fl">
                    <div class="big-like">
                        <div class="social-likes social-likes_visible social-likes_ready" data-url="{{ $post->link }}">
                            <div class="social-likes__widget social-likes__widget_facebook" title="{{ $post->name }}"><span class="social-likes__button social-likes__button_facebook"><span class="social-likes__icon social-likes__icon_facebook"></span><a href="https://www.facebook.com/sharer/sharer.php?u={{ $post->link }}"
                                       onclick="window.open(this.href, 'facebook-share','width=580,height=296');return false;">Facebook</a></span></div>
                            <div class="social-likes__widget social-likes__widget_twitter" data-via="sapegin" data-related="{{ $post->name }}" title="{{ $post->name }}"><span class="social-likes__button social-likes__button_twitter"><span class="social-likes__icon social-likes__icon_twitter"></span><a href="https://twitter.com/intent/tweet?text={{ urlencode($post->name) }}&amp;url={{ $post->link }}"
                                       onclick="window.open(this.href, 'twitter-share', 'width=550,height=235');return false;">Twitter</a></span></div>
                            <div class="social-likes__widget social-likes__widget_plusone" title="{{ $post->name }}"><span class="social-likes__button social-likes__button_plusone"><span class="social-likes__icon social-likes__icon_plusone"></span><a href="https://plus.google.com/share?url={{ $post->link }}"
                                       onclick="window.open(this.href, 'google-plus-share', 'width=490,height=530');return false;">Google+</a></span><span class="social-likes__counter social-likes__counter_plusone social-likes__counter_empty"></span></div>
                            <div id="chiase"></div>
                        </div>
                    </div>
                    <div class="like-plus">
                         <div class="nut" id="facebook">
                            <div class="fb-like" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div>
                         </div>
                        <div class="nut" id="google">
                            <script type="text/javascript" src="https://apis.google.com/js/plusone.js" gapi_processed="true"></script>
                            <div id="___plusone_0" style="text-indent: 0px; margin: 0px; padding: 0px; background: transparent; border-style: none; float: none; line-height: normal; font-size: 1px; vertical-align: baseline; display: inline-block; width: 90px; height: 20px;"><iframe ng-non-bindable="" frameborder="0" hspace="0" marginheight="0" marginwidth="0" scrolling="no" style="position: static; top: 0px; width: 90px; margin: 0px; border-style: none; left: 0px; visibility: visible; height: 20px;" tabindex="0" vspace="0" width="100%" id="I0_1494315073760" name="I0_1494315073760" src="https://apis.google.com/u/0/se/0/_/+1/fastbutton?usegapi=1&amp;size=medium&amp;origin=https%3A%2F%2Fbaohaiquanvietnam.vn&amp;url=https%3A%2F%2Fbaohaiquanvietnam.vn%2Fban-tin%2Fchinh-uy-hai-quan-trung-quoc-tham-truong-trung-cap-ky-thuat-hai-quan-viet-nam-3106.html&amp;gsrc=3p&amp;ic=1&amp;jsh=m%3B%2F_%2Fscs%2Fapps-static%2F_%2Fjs%2Fk%3Doz.gapi.en.8-nmspYvIxY.O%2Fm%3D__features__%2Fam%3DAQ%2Frt%3Dj%2Fd%3D1%2Frs%3DAGLTcCNWNGLvbBjsHogzHMLYAni_ZjJuew#_methods=onPlusOne%2C_ready%2C_close%2C_open%2C_resizeMe%2C_renderstart%2Concircled%2Cdrefresh%2Cerefresh&amp;id=I0_1494315073760&amp;parent=https%3A%2F%2Fbaohaiquanvietnam.vn&amp;pfname=&amp;rpctoken=49136480" data-gapiattached="true" title="+1"></iframe></div>
                        </div>
                        {{-- <div class="fb-comments" data-href="{{ $post->link }}" data-numposts="5" data-width="100%"></div> --}}
                    </div>
                </div>
                <div class="clearfix"></div>
                @include('theme::partial.othernews',['category_id'=>$post->post->categories->map->id->toArray()])
            </div>
            <div class="col-sm-4 navleft destop-pr0 tablet-pr0">
                @include('theme::partial.slidebar')
            </div>
        </div>
    </div>
</section>
@endsection
