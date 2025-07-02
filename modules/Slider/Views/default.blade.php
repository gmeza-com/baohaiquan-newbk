@push('header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/assets/owl.theme.default.min.css">
@endpush

@push('footer')
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.min.js"></script>
<script>
    'use strict';
    $('.owl-carousel').owlCarousel({
        loop:true,
        margin:10,
        dots: true,
        autoplay: true,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:3
            },
            1000:{
                items:5
            }
        }
    });
    </script>
@endpush

<div class="owl-carousel owl-theme">
    @foreach($widget->content->sortBy('position') as $content)
        <div class="item">
            <a href="{{ @$content['link'] ?: '#'}}" title="{{ @$content['language'][$current_language]['title'] }}">
                <img src="{{ @$content['language'][$current_language]['picture'] }}" alt="{{ @$content['language'][$current_language]['title'] }}">
            </a>
            <h4>{{ @$content['language'][$current_language]['title'] }}</h4>
            @if(@$content['language'][$current_language]['description'])
                <p>{{ @$content['language'][$current_language]['description'] }}</p>
            @endif
        </div>
    @endforeach
</div>