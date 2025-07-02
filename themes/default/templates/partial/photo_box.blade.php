@push('footer')
    <style>
        .phong_su_title{
            font-family: 'MyriadPro';
            font-weight: 800;
            display:block
        }
    </style>
@endpush
<section class="photo-interview">
    <div class="container">
        @php
            $cate_name = get_news_category_by_id(28);
        @endphp
        <a href="{{ $cate_name->language('link') }}" class="widget-title phong_su_title">
            <span class="icon"></span>
        <span>Phóng sự ảnh</span>
        </a>
@php
$featuredPost = get_list_news_posts(12, $category, true, 'featured');
@endphp
        <div class="slider owl-carousel">
            @foreach($featuredPost as $post)
            <div class="item featured-newest-item">
                <div class="thumbs-new center-thumb">
                    <a href="{{ $post->language('link') }}">
                        <img src="{{ thumbnail($post->thumbnail, null, null, 60) }}" alt="{{ $post->language('name') }}" style="height: 146px;" />
                        {{-- <span class="date-featured"><strong class="day">{{ $post->published_at->format('d') }}</strong><span>{{ $post->published_at->format('\Tm') }}</span></span> --}}
                    </a>
                </div>
                <div class="des-new">
                    <h3 class="title"><a href="{{ $post->language('link') }}">{{ $post->language('name') }}</a></h3>
                </div>
            </div>
            @endforeach
    </div><!-- .container -->
</section>
