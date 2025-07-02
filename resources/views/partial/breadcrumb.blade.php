<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ url('/') }}">{{ trans('language.home') }}</a></li>
    @foreach($breadcrumb as $item)
        <li>
            <a href="{{ $item['link'] }}">{{ $item['name'] }}</a>
        </li>
    @endforeach
</ul>