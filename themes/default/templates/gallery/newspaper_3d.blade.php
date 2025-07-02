@extends('theme::newspaper_3d')
@section('content')
@push('header')
    <style>
        .ads-img img{
            display: none;
        }
    </style>

    <link rel="stylesheet" type="text/css" href="{{ $theme_url }}/css/flipbook.style.css">

    <style type="text/css">
        .bookshelf .thumb{
            display: inline-block;
            cursor: pointer;
            margin: 0px 0.5%;
            width: 15% !important;
            box-shadow:0px 1px 3px rgba(0,0,0,.3);
            max-width: 250px;
            position: relative;
        }
        .bookshelf .thumb img{
            width:100%;
            display:block;
            vertical-align:top;
        }
        .bookshelf .shelf-img{
            z-index:0;
            height: auto;
            max-width: 100%;
            vertical-align: top;
            margin-top:-12px;
            width: 100%;
        }
        .bookshelf .covers{
            width:100%;
            height:auto;
            z-index: 8;
            position: relative;
            text-align:center;
        }
        .bookshelf{
            text-align: center;
            padding:0px;
        }
        #Header{
            text-align: center;
            margin-bottom:30px;
            margin-top: 20px;
        }
        .bookshelf .shelf-img {
            display: block;
            margin: -3px auto 120px auto;
        }

        .bookshelf .thumb:hover .mask_newpaper {
            opacity: 1;
        }
        .mask_newpaper {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            color:#fff;
            font-weight: bold;
            font-size: 15px;
            display:flex;
            justify-content: center;
            align-items: center;
            background: rgba(51,51,51,0.5);
            z-index: 20;
            opacity: 0;
            text-align: center;
            transition: all 0.5s ease;
            -webkit-transition: all 0.5s ease;
            -moz-transition: all 0.5s ease;
            -o-transition: all 0.5s ease;
        }

    </style>

@endpush

<div id="Header">
    <nav id="topmenu">
        <form method="GET" action="{{ request()->url() }}">
            <span>Năm: </span>
            <select name="year" style="width: 80px" onchange="this.form.submit()">
                @foreach($listYears as $year)
                    <option value="{{ $year }}"{{ $year == $currentYear ? ' selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
        </form>
    </nav>
</div>


<div class="bookshelf">
    <div class="covers">
        @foreach($listNewspaper as $key_newspaper => $newspaper)
                {{-- @php
                    $key_newspaper++;
                @endphp

                <div class="thumb book-{{ str_slug($newspaper->language('name')) }}" data_year="{{ Request::get('year') ? Request::get('year') : end($listYears) }}" data_id="{{ $newspaper->id }}" data_slug="{{ str_slug($newspaper->language('name')) }}">
                    @if($newspaper->thumbnail)
                        @php
                            $src = $newspaper->thumbnail;
                        @endphp
                        <img src="{{ $src }}">
                    @else
                        @php
                            $src = $newspaper->language('content')->map->picture->first();
                        @endphp
                        <img src="{{ $src }}">
                    @endif


                    <div class="mask_newpaper" data_year="{{ Request::get('year') ? Request::get('year') : end($listYears) }}" data_id="{{ $newspaper->id }}" data_slug="{{ str_slug($newspaper->language('name')) }}">
                        {{ $newspaper->language('name') }}  ({{ $newspaper->published_at->format('d.m.Y')}})
                    </div>
                </div>

                @if($key_newspaper % 4 == 0)
                    <img class="shelf-img" src="{{ $theme_url }}/images/book/shelf_wood.png">
                @endif --}}

                <div class="thumb book-{{ str_slug($newspaper->language('name')) }}" data_year="{{ Request::get('year') ? Request::get('year') : end($listYears) }}" data_id="{{ $newspaper->id }}" data_slug="{{ str_slug($newspaper->language('name')) }}">
                    @if($newspaper->thumbnail)
                        @php
                            $src = $newspaper->thumbnail;
                        @endphp
                        <img src="{{ $src }}">
                    @else
                        @php
                            $src = $newspaper->language('content')->map->picture->first();
                        @endphp
                        <img src="{{ $src }}">
                    @endif


                    <div class="mask_newpaper" data_year="{{ Request::get('year') ? Request::get('year') : end($listYears) }}" data_id="{{ $newspaper->id }}" data_slug="{{ str_slug($newspaper->language('name')) }}">
                        {{ $newspaper->language('name') }}  ({{ $newspaper->published_at->format('d.m.Y')}})
                    </div>
                </div>

                @if($loop->iteration % 4 == 0 || $loop->last)
                <img class="shelf-img" src="{{ $theme_url }}/images/book/shelf_wood.png">
                @endif
        @endforeach
    </div>
</div>

<div class="paginate-newpaper">
    {{ $listNewspaper->appends(['year' => request()->get('year')])->links() }}
</div>

@push('footer')
    <script src="{{ $theme_url }}/js/flipbook.min.js"></script>

    <script type="text/javascript">

        // $(document).ready(function(){
        //     $(".thumb").flipBook({
        //         pages: [{"src":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/888888.jpg","thumb":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/888888.jpg"},{"src":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/77777777.jpg","thumb":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/77777777.jpg"},{"src":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/632666666.jpg","thumb":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/632666666.jpg"},{"src":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/5v5421.jpg","thumb":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/5v5421.jpg"},{"src":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/4vft555555.jpg","thumb":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/4vft555555.jpg"},{"src":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/3fffffffff.jpg","thumb":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/3fffffffff.jpg"},{"src":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/2t678888.jpg","thumb":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/2t678888.jpg"},{"src":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/gt55551.jpg","thumb":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/gt55551.jpg"}],
        //         lightBox:true
        //     });
        // });


        // $(document).on('click','.mask_newpaper',function(){
        //     $(".thumb").flipBook({
        //         pages: [{"src":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/888888.jpg","thumb":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/888888.jpg"},{"src":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/77777777.jpg","thumb":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/77777777.jpg"},{"src":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/632666666.jpg","thumb":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/632666666.jpg"},{"src":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/5v5421.jpg","thumb":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/5v5421.jpg"},{"src":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/4vft555555.jpg","thumb":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/4vft555555.jpg"},{"src":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/3fffffffff.jpg","thumb":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/3fffffffff.jpg"},{"src":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/2t678888.jpg","thumb":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/2t678888.jpg"},{"src":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/gt55551.jpg","thumb":"https:\/\/baohaiquanvietnam.vn\/storage\/users\/user_6\/gt55551.jpg"}],
        //         lightBox:true
        //     })

        // });

        var loadedNewspapers = [];

        $(document).on('click','.mask_newpaper',function(){
            var _this = $(this);
            var slug = _this.attr('data_slug');
            var year = _this.attr('data_year');
            var id = _this.attr('data_id');

            if (loadedNewspapers.includes(id)) {
                return;
            }

            $.ajax({
                url:"/load_newspaper?id_cate=" + id + "&year="+year ,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    _this.flipBook({
                        pages: data,
                        lightBox:true
                    });

                    loadedNewspapers.push(id);

                    _this.trigger('click');
            }
        });

        })
    </script>
@endpush


@stop

