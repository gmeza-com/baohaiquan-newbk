<base href="{{asset('')}}">
<link rel="stylesheet" href="/backend/css/bootstrap.min.css">
<link rel="stylesheet" href="/backend/css/plugins.css">
<link rel="stylesheet" href="/assets/vendor/toastr/toastr.min.css">
<link rel="stylesheet" href="/assets/vendor/sweetalert2/sweetalert2.min.css">
<link rel="stylesheet" href="/assets/vendor/flag/css/flag-icon.min.css">
<link rel="icon" href="{{ option('site_favicon') }}">

<script>
    window.CNV = {!! json_encode([
            'baseUrl'  => url('/'),
            'csrfToken' => csrf_token(),
            'isLogin' => Auth::check()
        ]) !!};
</script>