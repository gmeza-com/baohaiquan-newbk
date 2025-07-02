<h1 class="text-center video-title data-id-{{ $gallery->id }}">{{ $gallery->name }}</h1>
<div class="content">
    {!! @$gallery->content['content'] !!}
    @if(\App\Libraries\Str::isYoutubeLink(@$gallery->language('content')->get('link')))
        <div class="embed-responsive embed-responsive-4by3">
            <iframe class="embed-responsive-item vjs-16-9" src="{{ \App\Libraries\Str::parseYoutubeLinkToEmbed(@$gallery->content['link']) }}" frameborder="0" allowfullscreen></iframe>
        </div>
    @else
        <div class="wrapper">
             <div class="videocontent">
                <video id="my-video" align="center"  class="video-js vjs-default-skin vjs-big-play-centered vjs-16-9" width="640" height="264" controls poster="{{ @$gallery->thumbnail }}" preload="auto" data-setup='{"fluid": true}'>
                    <source src="{{ $gallery->language('content')->get('link') }}" type='video/mp4'>
                    <p class="vjs-no-js">
                        To view this video please enable JavaScript, and consider upgrading to a web browser that
                        <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                    </p>
                </video>
             </div>
        </div>
        
    @endif
</div>
<div class="meta">
    <span class="updated_at">
        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
        {!! trans('gallery::web.published_at', ['datetime' => $gallery->published_at->format('d-m-Y h:s')])  !!}
    </span>
    <span class="views">
        <i class="fa fa-eye" aria-hidden="true"></i>
        {!! trans('gallery::web.view_count', ['count' => $gallery->view->count]) !!}
    </span>
</div>
