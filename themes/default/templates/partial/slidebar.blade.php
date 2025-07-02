@php
$isShowSidebar = true;

if (isset($category) && isset($category->category) && $category->category->id == 32) {
  $isShowSidebar = false;
}

if (isset($post) && isset($post->post) && in_array(32, $post->post->categories->map->id->toArray())) {
  $isShowSidebar = false;
}
@endphp

@if($isShowSidebar)
<aside class="featured-news-widget widget">
    <h3 class="widget-title bg"><span>Tin theo chuyên mục</span></h3>
    <div class="featured-news-slider">
        <ul class="tcm">
        @foreach(cnv_menu('menu') as $item)
        <li class="{{ $item->children->count() ? 'father' : '' }}" >
            <a class="{{ $item->children->count() ? 'f18' : '' }}" href="{{ @$item->attributes['url'] == '#' ? 'javascript:void(0);' :  @$item->attributes['url'] }}" {!! @$item->attributes_html !!}>
                <i class="fa fa-angle-double-right"></i>{{ @$item->language('name') }}
            </a>
            @if($item->children->count())
                <ul>
                @foreach($item->children as $item2)
                    <li class="sub"><a href="{{ @$item2->attributes['url'] == '#' ? 'javascript:void(0);' :  @$item2->attributes['url'] }}" {!! @$item2->attributes_html !!}>{{ @$item2->language('name') }}</a></li>
                @endforeach
                </ul>
            @endif
        </li>
        @endforeach
        </ul>
    </div><!-- .featured-news-slider -->
</aside>
@endif
