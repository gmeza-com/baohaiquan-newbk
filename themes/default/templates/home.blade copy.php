@extends('theme::layout')

@section('content')
    <main id="main" class="site-main">
        <div class="container">
            <div class="row">
                <div id="content" class="site-content col-md-8">
                    @include('theme::partial.slider')
                    <section class="featured-new foreign">
                        @include('theme::partial.news_box', ['category' => 1])
                    </section>
                    <!-- featured-new politic-featured -->
                    {{--                    <section class="featured-new foreign"> --}}
                    {{--                        @include('theme::partial.news_box',['category' => 33]) --}}
                    {{--                    </section> --}}

                    <section class="banner">
                        <div class="mt-10" style="margin-top: 10px">
                            <img src="/assets/images/KH.jpg"> <!--hqhd.png-->
                        </div>
                    </section>

                    <!-- featured-new politic-featured -->
                    <section class="featured-new culture-featured">
                        @include('theme::partial.news_box', ['category' => 6])
                    </section><!-- featured-new culture-featured -->
                    <!-- featured-new politic-featured -->
                    <section class="featured-new culture-featured">
                        @include('theme::partial.news_box', ['category' => 19])
                    </section><!-- featured-new culture-featured -->
                </div><!-- .site-content -->
                @include('theme::partial.video_box', ['category' => 2])

            </div>
        </div><!-- .container -->

        @include('theme::partial.photo_box', ['category' => 28])
        <!-- photo-interview -->

        <section class="news-bottom">
            <div class="container">
                <div class="row">
                    <div class="site-content col-md-8">
                        <section class="featured-new foreign">
                            @include('theme::partial.news_box', ['category' => 9])
                        </section><!-- foreign -->
                        <section class="featured-new law">
                            @include('theme::partial.news_box', ['category' => 12])
                        </section><!-- law -->

                        <section class="featured-new feeling">
                            @include('theme::partial.news_box', ['category' => 15])
                        </section><!-- featured-new feeling -->

                        {{--                            <section class="banner"> --}}
                        {{--                                <div class="mt-10" style="margin-top: 10px"> --}}
                        {{--                                    <img src="/assets/images/banner_1004_desktop.png"> --}}
                        {{--                                </div> --}}
                        {{--                            </section> --}}

                        <section class="featured-new history">
                            @include('theme::partial.news_box', ['category' => 24])
                        </section><!-- featured-new history -->

                        <section class="featured-new research">
                            @include('theme::partial.news_box', ['category' => 25])
                        </section><!-- research -->

                    </div><!-- site-conten -->
                    <div class="sidebar col-md-4">
                        {{-- @include('theme::partial.newspaper') --}}
                        @include('theme::partial.newspaper_3d')



                        @include('theme::partial.rss')
                        <aside class="weather-widget widget">
                            <h3 class="widget-title">
                                <span class="icon"></span><span></span>
                            </h3>
                            <!--
                                    <div class="weather-ct">
                                        <ul>
                                            <li>
                                                <span class="city">Khu vực</span>
                                                <span class="desc-weather">Thời tiết</span>
                                            </li>
                                            <li>
                                                <span class="city"><a href="http://thuydacvietnam.org.vn/dtd/57-bac_vinh_bac_bo" target="_blank">Bắc Vịnh Bắc Bộ</a></span>
                                                <img src="https://baohaiquanvietnam.vn/storage/images/muarao_vadong.gif">
                                            </li>
                                            <li>
                                                <span class="city"><a href="http://thuydacvietnam.org.vn/dtd/56-nam_vinh_bac_bo" target="_blank">Nam Vịnh Bắc Bộ</a></span>
                                                <img src="https://baohaiquanvietnam.vn/storage/images/muarao_vadong.gif">
                                            </li>
                                            <li>
                                                <span class="city"><a href="http://thuydacvietnam.org.vn/dtd/55-quang_tri_den_quang_ngai" target="_blank">Quảng Trị đến Quảng Ngãi</a></span>
                                                <img src="https://baohaiquanvietnam.vn/storage/images/muarao_vadong.gif">
                                            </li>
                                            <li>
                                                <span class="city"><a href="http://thuydacvietnam.org.vn/dtd/54-binh_dinh_den_ninh_thuan" target="_blank">Bình Định đến Ninh Thuận</a></span>
                                                <img src="https://baohaiquanvietnam.vn/storage/images/Co_luc_co_Mua.gif">
                                            </li>
                                            <li>
                                                <span class="city"><a href="http://thuydacvietnam.org.vn/dtd/53-binh_thuan_den_ca_mau" target="_blank">Bình Thuận đến Cà Mau</a></span>
                                                <img src="https://baohaiquanvietnam.vn/storage/images/Mua dong vai noi.gif">
                                            </li>
                                            <li>
                                                <span class="city"><a href="http://thuydacvietnam.org.vn/dtd/52-ca_mau_den_kien_giang" target="_blank">Cà Mau đến Kiên Giang</a></span>
                                                <img src="https://baohaiquanvietnam.vn/storage/images/muarao_vadong.gif">
                                            </li>
                                            <li>
                                                <span class="city"><a href="http://thuydacvietnam.org.vn/dtd/51-bac_bien_dong" target="_blank">Bắc Biển Đông</a></span>
                                                <img src="https://baohaiquanvietnam.vn/storage/images/Mua dong vai noi.gif">
                                            </li>
                                            <li>
                                                <span class="city"><a href="http://thuydacvietnam.org.vn/dtd/50-quan_dao_hoang_sa" target="_blank">Quần đảo Hoàng Sa</a></span>
                                                <img src="https://baohaiquanvietnam.vn/storage/images/Mua dong vai noi.gif">
                                            </li>
                                            <li>
                                                <span class="city"><a href="http://thuydacvietnam.org.vn/dtd/49-giua_bien_dong" target="_blank">Giữa Biển Đông</a></span>
                                                <img src="https://baohaiquanvietnam.vn/storage/images/Mua dong vai noi.gif">
                                            </li>
                                            <li>
                                                <span class="city"><a href="http://thuydacvietnam.org.vn/dtd/48-quan_dao_truong_sa" target="_blank">Quần đảo Trường Sa</a></span>
                                                <img src="https://baohaiquanvietnam.vn/storage/images/Co_luc_co_Mua.gif">
                                            </li>
                                            <li>
                                                <span class="city"><a href="http://thuydacvietnam.org.vn/dtd/47-nam_bien_dong" target="_blank">Nam Biển Đông</a></span>
                                                <img src="https://baohaiquanvietnam.vn/storage/images/Co_luc_co_Mua.gif">
                                            </li>

                                        </ul>
                                        <p class="time-update">Cập nhật : <a href="http://thuydacvietnam.org.vn/dtd/48-quan_dao_truong_sa" target="_blank">05:34:SA - 10/05/2017 </a></p>
        </div>
     -->
                        </aside><!-- weather-widget -->
                        <aside class="widget ads-widget">
                            {!! widget('ads-sidebar') !!}
                        </aside>
                    </div><!-- sidebar -->
                </div><!-- row -->
            </div><!-- container -->
        </section>

    </main><!-- .site-main -->
@stop
