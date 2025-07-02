<header id="header" class="site-header">
    <div class="top-header">
        <div class="container">
            <div class="site-brand">
		<a href="{{ get_option('site_url') }}">
		<img src="{{ get_option('site_logo') }}?t=123123123" alt="{{ get_option('site_name') }}">
		<!--	<img src="/storage/images/QC/logo1_gray.png"> -->
		</a>
            </div>
        </div><!-- .container -->
    </div><!-- .top-header -->
    {{-- <img class="img-responsive" src="{{ $theme_url }}/images/banner_tet-01.jpg"> --}}

    <nav class="main-menu">
        <div class="container">
            <span class="mobile-menu"></span>
            @include('theme::partial.menu', ['menus' => cnv_menu('menu'), 'class' => 'menu-main'])
        </div>
    </nav><!-- .main-menu -->
{{--    <img src="/assets/images/banner-web.png" class="img-responsive">--}}
    <div class="bot-header">
        <div class="container">
            {{-- <div style="padding-bottom: 10px;" class="parent_banner"><img src="{{ $theme_url }}/cmnm2019.jpg" width="1173px"></div> --}}
	    

<div class="row"> <div class="col-md-12" style="
">
<!--<img src="/assets/images/tet_2025_desktop.png"> -->  </div>

            </div>


<div class="row">
                @if(!request()->segment(1) && widget('homepage-banner'))
                <div class="col-md-12 marquee">
                    {!! widget('homepage-banner') !!}
                </div>
                @endif

                <div class="col-md-8">
                    <ul class="list-inline">
                        @php
                            $dayOfWeek = [
                                'Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'
                            ];
                            $now = Carbon\Carbon::now();
                        @endphp
                        <li>{{ $dayOfWeek[$now->format('w')] }}, ngày {{ $now->format('d') }} tháng {{ $now->format('m') }} năm {{ $now->format('Y') }}</li>
                        <li><span>Tòa soạn:</span>Số 3B Trần Hưng Đạo, Hồng Bàng, Hải Phòng</li>
                        <li><span>Email:</span> <a href="mailto:{{ get_option('site_email') }}">{{ get_option('site_email') }}</a></li>
                    </ul>
                </div>

                <div class="col-md-4">
                    <form class="searchform" action="/blogs/search">
                        <input class="search-field" type="text" placeholder="Nhập từ khóa tìm kiếm" name="q" />
                        <div class="button1" id="searchSubmit1">
                            <button type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div><!-- .container -->
    </div><!-- .bot-header -->
</header><!-- .site-header -->
