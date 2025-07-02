<!DOCTYPE html>
<!--[if IE 9]>
<html class="no-js lt-ie10" lang="en"> <![endif]-->
<!--[if gt IE 9]><!-->
<html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ (isset($title) ? $title .' - ' : '') . config('app.name') }}</title>
    <meta name="description" content="{{ isset($description) ? $description : get_option('site_description') }}">
    @include('partial.cms_head')
    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="{{ $theme_url }}/css/flipbook.css" />
    <link rel="stylesheet" type="text/css" href="{{ $theme_url }}/css/newspaper.css" />
    <!-- END Stylesheets -->
    @stack('header')
    {!! get_option('facebook_pixel') !!}
</head>
<body>
     <div id="mwrapper">
            @yield('content')
    </div>

{!! get_option('google_analytics') !!}
{!! get_option('google_remaketing') !!}
{!! get_option('livechat') !!}

@include('partial.cms_footer')
<script type="text/javascript" src="{{ $theme_url }}/js/hash.js"></script>
<script type="text/javascript" src="{{ $theme_url }}/js/modernizr.2.5.3.min.js"></script>
<script type="text/javascript" src="{{ $theme_url }}/js/flipbook.js"></script>
@stack('footer')

</body>
</html>
