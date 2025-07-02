@php
    $category = app()->make(\Modules\News\Repositories\PostCategoryRepository::class)->getViaId($category);
@endphp
<h2 class="widget-title">
    <span class="icon"></span>
    <span><a href="{{ @$category->language('link') }}">{{ @$category->language('name') }}</a></span>
</h2>

@if(! $category->children->isEmpty())
<ul class="list-title">
    @foreach($category->children as $child)
    <li><a href="{{ $child->language('link') }}">{{ $child->language('name') }}</a></li>
    @endforeach
</ul>
@endif

@php
$posts = get_list_news_posts(7, $category->id, true, 'published');
$featuredPost  = false;
$latestPosts = collect([]);

if (!$posts->isEmpty()) {
   $featuredPost = $posts->first();
   $latestPosts = $posts->filter(function ($model) use ($featuredPost)
   {
       return $model->id !== $featuredPost->id;
   });
}
@endphp

@if($featuredPost)
<div class="featured-newest">
    <div class="featured-newest-item row">
        <div class="col-md-6">
            <div class="thumbs-new left-thumb">
                <a href="{{ $featuredPost->language('link') }}" class="first_img">
                    <img src="{{ thumbnail($featuredPost->thumbnail, null, null, 60) }}" alt=""/>
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="des-new">
                <h3 class="title"><a href="{{ $featuredPost->language('link') }}">{{ $featuredPost->language('name') }}</a></h3>
                <p>{{ $featuredPost->language('description') }}</p>
                {{-- <span class="date"><i class="fa fa-clock-o"></i> {{ $featuredPost->published_at->format('d/m/Y') }}</span> --}}
                <a class="show-more" href="{{ $featuredPost->language('link') }}">Xem tiếp <i class="fa fa-arrow-circle-o-right"></i></a>
            </div>
        </div>
    </div>
</div>
@endif

<div class="featured-new-list featured-news-slider">
    <ul class="list-ft-new clearfix">
        @foreach($latestPosts as $post)
        @if($loop->iteration < 3)
            <li>
                <div class="img-list">
                    <a class="thumb" href="{{ $post->language('link') }}"><img src="{{ thumbnail($post->thumbnail, null, null, 60) }}" alt="{{ $post->language('name') }}"/></a>
                </div>
                <div class="info">
                    <h4><a href="{{ $post->language('link') }}">{{ $post->language('name') }}</a></h4>
                    {{-- <span class="date"><i class="fa fa-clock-o"></i> {{ $post->published_at->format('d/m/Y') }}</span> --}}
                </div>
            </li>
        @else
            <div class="info11">
                <h4>
                    <a href="{{ $post->language('link') }}">
                        <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> {{ $post->language('name') }}
                    </a>
                </h4>
            </div>
        @endif
        @endforeach

    </ul>
</div><!-- featured-new-list -->
