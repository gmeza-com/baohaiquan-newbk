<meta property="og:site_name" content="{{ get_option('site_name') }}" />
<meta property="og:type" content="{{ @$type }}" />
<meta property="og:title" content="{{ @$title }}" />
<meta property="og:description" content="{{ @$description }}" />
<meta property="og:url" content="{{ request()->url() }}" />
@if(@$image)
<meta property="og:image" content="{{ $image }}" />
@endif
@if(@$published_time)
<meta property="article:published_time" content="{{ $published_time->toIso8601String() }}" />
@endif
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ @$title }}" />
<meta name="twitter:description" content="{{ @$description }}" />
<meta name="twitter:url" content="{{ request()->url() }}" />
@if(@$image)
<meta property="twitter:image" content="{{ $image }}" />
@endif