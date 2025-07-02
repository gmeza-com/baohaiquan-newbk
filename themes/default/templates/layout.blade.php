<!DOCTYPE html>
<html class="no-js" lang="vi">

<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name=viewport content="width=device-width, initial-scale=1">
  <title>{{ (isset($title) ? $title .' - ' : '') . config('app.name') }}</title>
  <meta name="description" content="{{ isset($description) ? $description : get_option('site_description') }}">
  <meta property="fb:app_id" content="1632979913458192" />
  <meta property="og:image" content="https://baohaiquanvietnam.vn/storage/images/logo2.png">
  <meta property="og:description" content="Chuyên Trang Báo Chính Thức Của Hải Quân Nhân Dân Việt Nam">
  <meta property="og:title" content="Báo Hải Quân Việt Nam">

  @include('partial.cms_head')
  <link href="{{ $theme_url }}/js/video-js.css" rel="stylesheet">
  <!-- If you'd like to support IE8 -->
  <script src="{{ $theme_url }}/js/videojs-ie8.min.js"></script>
  <!-- Stylesheets -->
  <link rel="stylesheet" type="text/css" href="{{ $theme_url }}/style.css?t=4392384783b" />
  <link rel="stylesheet" type="text/css" href="{{ $theme_url }}/css/responsive.css?t=143299c" />
  <link rel="stylesheet" type="text/css" href="{{ $theme_url }}/css/module.css" />
  <!-- END Stylesheets -->
  @stack('header')
  {!! get_option('facebook_pixel') !!}
  <script>
    window.fbAsyncInit = function() {
      FB.init({
        appId: '1632979913458192',
        xfbml: true,
        version: 'v2.12'
      });
      FB.AppEvents.logPageView();
    };

    (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) {
        return;
      }
      js = d.createElement(s);
      js.id = id;
      js.src = "https://connect.facebook.net/en_US/sdk.js";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
  </script>
  @include('partial.cms_footer')
  <script src="{{ $theme_url }}/js/video.js"></script>
  <script type="text/javascript" src="{{ $theme_url }}/libs/owl.carousel/owl.carousel.min.js"></script>
  <script type="text/javascript" src="{{ $theme_url }}/libs/jquery.bxslider/jquery.bxslider.min.js"></script>
  <!-- orther script -->
  <script type="text/javascript" src="{{ $theme_url }}/js/main.js"></script>

  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-ZR0MP4K5V3"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-ZR0MP4K5V3');
  </script>

</head>

<body>
  <div id="wrapper">
    @include('theme::partial.header')

    <main id="main" class="site-main">
      <div class="container banner-tet-2025">
        <!-- <img src="/assets/images/tet_2025_mobile.png"> -->

      </div>

      @yield('content')
    </main>
    @include('theme::partial.footer')
  </div>
  {!! get_option('google_analytics') !!}
  {!! get_option('google_remaketing') !!}
  {!! get_option('livechat') !!}
  @stack('footer')
  <div class="back-top"><i class="fa fa-angle-double-up"></i></div>
</body>

</html>