@php
    $category = app()->make(\Modules\Gallery\Repositories\GalleryCategoryRepository::class)->getViaId($category);
@endphp
<aside class="tv-news-widget widget">
    <h3 class="widget-title">
            <span class="icon"></span>
        <span>{{ @$category->language('name') }}</span></h3>
    <div class="featured-newest">

        @php
        $latestPosts = get_list_gallery(6, $category->id, true, 'featured');
            $featuredPost = false;

            if (! $latestPosts->isEmpty()) {
               $featuredPost = $latestPosts->first();
               $latestPosts = $latestPosts->reject(function($gallery, $index) use ($featuredPost) {
                    return $featuredPost->id == $gallery->id;
               });
            }

        @endphp
        @if($featuredPost)
        <div class="featured-newest-item">
            <div class="thumbs-new">
                <a href="{{ $featuredPost->language('link') }}">
                    <img src="{{ $featuredPost->thumbnail }}" alt=""/>
                    <span class="date-featured"><strong class="day">{{ $featuredPost->published_at->format('d') }}</strong><span>{{ $featuredPost->published_at->format('\Tm') }}</span></span>
                </a>
            </div>

            <div class="des-new">
                <h3 class="title fix-title"><a href="{{ $featuredPost->language('link') }}">{{ $featuredPost->language('name') }}</a></h3>
                {{ $featuredPost->language('description') }}
            </div>

        </div>
        @endif

        <div class="featured-news-slider">
            <ul class="list-ft-news clearfix">
                @foreach($latestPosts as $post)
                <li style="margin-bottom: 25px;">
                    <a class="thumb" href="#">
                        <img src="{{ true ? $post->thumbnail : thumbnail($post->thumbnail, null, null, 60) }}" alt="">
                    </a>
                    <div class="info">
                        <h4><a href="{{ $post->language('link') }}">{{ $post->language('name') }}</a></h4>
                    </div>
                </li>
                @endforeach
            </ul>
        </div><!-- .featured-news-slider -->
    </div>
    </aside>
