@php
    $category = app()->make(\Modules\Gallery\Repositories\GalleryCategoryRepository::class)->getViaId($category);
@endphp

@php
$posts = get_list_gallery(6, $category->id, true, 'featured');
$featuredPost = false;

if (! $posts->isEmpty()) {
   $featuredPost = $posts->first();
   $posts = $posts->reject(function($gallery, $index) use ($featuredPost) {
        return $featuredPost->id == $gallery->id;
   });
}
@endphp

@if($featuredPost)
<div class="sidebar col-md-4">
    <aside class="video-widget widget">
        <h3 class="widget-title bg"><span class="widget-title-fix">{{ @$category->language('name') }}</span> <a href="{{ @$category->language('link') }}">Xem tất cả</a></h3>
        @if($featuredPost)
        <div class="video-wd">
            <video id="my-video" align="center" class="video-js vjs-default-skin vjs-big-play-centered vjs-16-9" controls poster="{{ $featuredPost->thumbnail }}" preload="auto" width="370" height="208" data-setup="{}">
                <source src="{{ $featuredPost->language('content')->get('link') }}" type='video/mp4'>
                <p class="vjs-no-js">
                    To view this video please enable JavaScript, and consider upgrading to a web browser that
                    <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                </p>
            </video>
            <h3 class="title"><a href="{{ @$featuredPost->language('link') }}">{{ @$featuredPost->language('name') }}</a></h3>
            <p>{{ $featuredPost->language('description') }}</p>
        </div>
        @else
        @endif
        <div class="video-wd-more">
            @foreach($posts as $latest)
            <div class="item">
                <a class="thumb" href="{{ $latest->language('link') }}"><img src="{{ thumbnail($latest->thumbnail, null, null, 60) }}" alt=""></a>
                <h4><a href="{{ $latest->language('link') }}">{{ $latest->language('name') }}</a></h4>
            </div>
            @endforeach
        </div><!-- .video-wd-more -->
    </aside>
    @include('theme::partial.featured_post')
    @include('theme::partial.tv_box',['category' => 1])
</div>
@endif
