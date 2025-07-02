<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
        <title>{{ (isset($title) ? $title .' - ' : '') . config('app.name') }}</title>
        <!-- Style CSS -->
        <link rel="stylesheet" type="text/css" href="{{ $theme_url }}/style.css" />
        <link href='{{ $theme_url }}/css/bootstrap.min.css' rel='stylesheet' type='text/css'  media='all'/>
        <link href="{{ $theme_url }}/css/social-likes_birman.css" rel="stylesheet" type="text/css" />

        <script type="text/javascript" src="{{ $theme_url }}/js/jquery-1.10.2.js"></script>
        <script type="text/javascript" src="{{ $theme_url }}/js/bootstrap.min.js"></script>
        <script src='{{ $theme_url }}/js/social-likes.js' type='text/javascript'></script>
    </head>
    <body>
        <section id="include">
            <div class="container">
                <div class="row">
                    <div class="col-xs-9">
                        <img src="{{ get_option('site_logo') }}">
                    </div>
                    <div class="col-xs-3">
                        <a href="javascript:;" onclick="return window.print();" title="print" class="intrang"> <span>In trang</span> (Ctr + P)</a>
                    </div>
                </div>
                <div class="line"></div>
                <div class="row ">
                    <div class="col-md-12">
                        @php
                            $dayOfWeek = [
                                'Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'
                            ];
                            $now = Carbon\Carbon::now();
                        @endphp
                        <div class="date-time">{{ $dayOfWeek[$post->post->published_at->format('w')] }}, {{ $post->post->published_at->format('d/m/Y h:m:s') }}</div>

                        <h1 class="title-news-detail-left">{{ $post->name }}</h1>
                        @if($post->second_name)
                        <h2 class="title-news-detail">{{ $post->second_name }}</h2>
                        @endif
                        @if($post->third_name)
                        <h3 class="title-news-detail">{{ $post->third_name }}</h2>
                        @endif

                        <div class="que_news">
                            <p class="hqol">{{ $post->post->prefix }}</p>
                            <p class="hqmain"></p>
                            <p style="text-align: justify;">
                            <strong>{!! $post->quote !!}</strong>
                            </p>
                        </div>
                        <div class="content_news">

                        {!! $post->content !!}

                        @if($post->note)
                        <blockquote>
                            {!! $post->note !!}
                        </blockquote>
                        @endif

                        </div>

                        <div class="clearfix"></div>

                            <div class="messenger_single">
                             {!! widget('dong-gop-bai-viet') !!}
                            </div>

                    </div>
                </div>
            </div>
        </section>
        <style type="text/css">
            .line{
                background: #ccc;
                height: 1px;
                width: 100%;
                float: left;
                margin: 5px 0 0 0;
            }
            .intrang{
                margin-top: 38px;
                font-size: 11px;
                display: block;
                color: #676767;
            }
            .title-news-detail{
                margin-bottom: 5px;
                color: #444444;
                text-align: center;
                font-size: 21px
            }
            h1.title-news-detail-left {
                color: #444444;
                font-size: 21px;
                margin: 0;
            }
            .date-time{
                color: #8c8c8c;
                padding: 15px 0;
            }
            p.hqol {
                float: left;
                margin-right: 5px;
                font-weight: bold;
                margin-bottom: 0;

            }
            .que_news {
                font-size: 14.5px;
                margin: 15px 0px;
                color: #666666;
            }
        </style>
    </body>
</html>
