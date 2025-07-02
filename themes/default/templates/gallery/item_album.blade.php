@if($featured)
<article>
    <h2 class="text-center video-title test1">
        <a href="{{ $featured->language('link') }}">{{ $featured->language('name') }}</a>
    </h2>
    <div class="post-thumbnail">
        @include('theme::gallery.video_box',['gallery'=>$featured])
    </div>
</article>
@else
<article>
    <h2 class="text-center video-title test2">
        <a href="{{ $item->language('link') }}">{{ $item->language('name') }}</a>
    </h2>
    <div class="post-thumbnail">
        @include('theme::gallery.video_box',['gallery'=>$item])
    </div>
</article>
@endif
