@php $f = get_list_news_featured_posts(6, 0, true, 'featured'); @endphp
@if(! $f->isEmpty())
<section class="focus-news">
    <div class="row">
        <div class="first-news col-md-9">
            <div class="slider owl-carousel">
                @foreach($f as $featured)
                <div class="item">
                    <a href="{{ $featured->language('link') }}"><img src="{{ thumbnail($featured->thumbnail, null, null, 60) }}" alt=""/></a>
                    <div class="des-new-slider">
                        <h3 class="title"><a href="{{ $featured->language('link') }}">{{ $featured->language('name') }}</a></h3>
                        <div class="meta">
                            <span class="date"><i class="fa fa-clock-o"></i>{{ $featured->published_at->format('d-m-y') }}</span>
                            <span class="cate">{!! implode(', ', $featured->list_categories) !!}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div><!-- slider owl-carousel -->
        </div>
        <!-- first-news -->
        <div class="focus-small-new col-md-3">
            <h2 class="widget-title"><span class="widget-title-fix">Tin mới</span></h2>
            <ul class="list-new">
                @foreach($f as $post)
                <li>
                    <h3 class="title"><a href="{{ $post->language('link') }}">{{ $post->language('name') }}</a></h3>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</section><!-- focus-news -->
@endif
