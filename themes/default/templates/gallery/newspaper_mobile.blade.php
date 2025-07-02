@extends('theme::newspaper')
@push('header')
<link href="{{ $theme_url }}/css/photoswipe.css" rel="stylesheet">
<link href="{{ $theme_url }}/css/default-skin.css" rel="stylesheet">
@endpush
@push('footer')
<script src="{{ $theme_url }}/js/photoswipe.min.js"></script>
<script src="{{ $theme_url }}/js/photoswipe-ui-default.min.js"></script> 
<script>
    'use strict';
    /* global jQuery, PhotoSwipe, PhotoSwipeUI_Default, console */
    (function($){
      // Init empty gallery array
      var container = [];
      // Loop over gallery items and push it to the array
      $('#Gallery').find('li').each(function(){
        var $link = $(this).find('a'),
            item = {
              src: $link.attr('href'),
              w: $link.data('width'),
              h: $link.data('height'),
              title: $link.data('caption')
            };
        container.push(item);
      });
      // Define click event on gallery item
      $('a').click(function(event){
        // Prevent location change
        event.preventDefault();
        // Define object and gallery options
        var $pswp = $('.pswp')[0],
            options = {
              index: $(this).parent('li').index(),
              bgOpacity: 0.85,
              showHideOpacity: true
            };
        // Initialize PhotoSwipe
        var gallery = new PhotoSwipe($pswp, PhotoSwipeUI_Default, container, options);
        gallery.init();
      });
    }(jQuery));
  </script>
@endpush
@section('content')
<div id="mwrapper">
<div id="Header">
    <a href="/" style="position: fixed;left: 10px;top: 4px; color: #fff; background: #c11902;z-index: 999999;display: block;padding: 7px 20px;border-radius: 30px;text-decoration: none;">Quay lại</a>
    <nav id="topmenu">
        <form method="GET" action="{{ request()->url() }}">
            <span>Kỳ: </span>
            <select name="id" class="inputbox" size="1" onchange="this.form.submit()">
                @foreach($listNewspaper as $newspaper)
                    <option value="{{ $newspaper->id }}"{{ $currentNewspaper->id == $newspaper->id ? ' selected' : '' }}>{{ $newspaper->language('name') }} ({{ $newspaper->published_at->format('d.m.Y')}})</option>
                @endforeach
            </select>
            <select name="year" onchange="this.form.submit()">
                @foreach($listYears as $year)
                    <option value="{{ $year }}"{{ $year == $currentYear ? ' selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
        </form>
    </nav>
</div>
<div id="MainContent">
   <ul id="Gallery" class="gallery">
    @foreach($currentNewspaper->language('content')->sortBy('position') as $album)
    <li itemprop="associatedMedia" itemscope>
        <a href="{{ @$album['picture'] }}" data-width="1500" data-height="2100" itemprop="contentUrl">
          <img src="{{ @$album['picture'] }}" itemprop="thumbnail">
        </a>
    </li>
    @endforeach
    </ul>
<div id="Footer">
    <p>Bản quyền thuộc về Báo điện tử Hải Quân.<p>
</div>
  <!-- Root element of PhotoSwipe. Must have class pswp. -->
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
  <!-- Background of PhotoSwipe. 
       It's a separate element as animating opacity is faster than rgba(). -->
  <div class="pswp__bg"></div>
  <!-- Slides wrapper with overflow:hidden. -->
  <div class="pswp__scroll-wrap">
      <!-- Container that holds slides. 
          PhotoSwipe keeps only 3 of them in the DOM to save memory.
          Don't modify these 3 pswp__item elements, data is added later on. -->
      <div class="pswp__container">
          <div class="pswp__item"></div>
          <div class="pswp__item"></div>
          <div class="pswp__item"></div>
      </div>
      <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
      <div class="pswp__ui pswp__ui--hidden">
          <div class="pswp__top-bar">
              <!--  Controls are self-explanatory. Order can be changed. -->
              <div class="pswp__counter"></div>
              <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
              <button class="pswp__button pswp__button--share" title="Share"></button>
              <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
              <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
              <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
              <!-- element will get class pswp__preloader--active when preloader is running -->
              <div class="pswp__preloader">
                  <div class="pswp__preloader__icn">
                    <div class="pswp__preloader__cut">
                      <div class="pswp__preloader__donut"></div>
                    </div>
                  </div>
              </div>
          </div>
          <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
              <div class="pswp__share-tooltip"></div> 
          </div>
          <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
          </button>
          <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
          </button>
          <div class="pswp__caption">
              <div class="pswp__caption__center"></div>
          </div>
      </div>
  </div>
</div>
@stop
