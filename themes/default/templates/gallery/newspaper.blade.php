@extends('theme::newspaper')
@section('content')
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
            <select name="year" style="width: 80px" onchange="this.form.submit()">
                @foreach($listYears as $year)
                    <option value="{{ $year }}"{{ $year == $currentYear ? ' selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
        </form>
    </nav>
</div>
<div id="bodyflip">
    <div class="flipbook-viewport">
        <div class="container">
            <div class="flipbook">
                <div ignore="1" class="next-button"></div>
                <div ignore="1" class="previous-button"></div>
                @foreach($currentNewspaper->language('content')->sortBy('position') as $album)

                    <div style="background-image:url({{ @$album['picture'] }})"></div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<div class="flip-control">
    <div class="tray-button">
        <span id="prev"></span>
        <span id="zoomIn"></span>
        <span id="zoomOut"></span>
        <span id="next"></span>
    </div>
</div>
@stop
