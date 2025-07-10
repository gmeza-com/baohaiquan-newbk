<!DOCTYPE html>
<html class="no-js" lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ (isset($title) ? $title . ' - ' : '') . config('app.name') }}</title>

    @include('partial.cms_head')
    <!-- Stylesheets -->
    <link rel="stylesheet" href="/backend/css/main.css?v=<?php echo microtime(); ?>">
    <link rel="stylesheet" href="/backend/css/themes.css">
    <!-- END Stylesheets -->

    <!-- Font Roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400..700&display=swap" rel="stylesheet">
    <!-- End font Roboto  -->

    <!-- Modernizr (browser feature detection library) -->
    <script src="/backend/js/vendor/modernizr.min.js"></script>
    @stack('header')
</head>

<body>

    @yield('layout')

    <!-- jQuery, Bootstrap.js, jQuery plugins and Custom JS code -->
    @include('partial.cms_footer')
    <script src="/backend/js/app.js"></script>
    @stack('footer')

</body>

</html>
