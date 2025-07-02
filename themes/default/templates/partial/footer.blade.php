{!! widget('footer') !!}
<footer id="footer" class="site-footer">
    <div class="site-bottom">
        <div class="container">
            <div class="row">
                @foreach(cnv_menu('menu')->chunk(7) as $items)
                <div class="footer-widget-wrap">
                    @foreach($items as $item)
                        @if(@$item->attributes['icon'] == 'fa fa-home') @continue @endif
                        <div class="footer-widget">
                            <h3 class="widget-title">
                                <a href="{{ @$item->attributes['url'] == '#' ? 'javascript:void(0);' :  @$item->attributes['url'] }}" {!! @$item->attributes_html !!}>
                                    {{ @$item->language('name') }}
                                </a>
                            </h3>
                            @if($item->children->count())
                            <ul>
                                @foreach($item->children as $child)
                                    <li>
                                        <a href="{{ @$child->attributes['url'] == '#' ? 'javascript:void(0);' :  @$child->attributes['url'] }}" {!! @$child->attributes_html !!}>
                                            {{ @$child->language('name') }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                    @endforeach
                </div><!-- .footer-widget-wrap -->
                @endforeach
            </div>
        </div><!-- .container -->
    </div>

    <div class="bot-footer">
        <div class="footer-contact">
            <div class="container">
                <ul class="list-inline">
                    <li><i class="fa fa-map-marker"></i> {{ get_option('site_address') }}, quận Hồng Bàng, thành phố Hải Phòng</li>
                    <li><i class="fa fa-phone"></i> ĐT: {{ get_option('site_phone') }}</li>
                    <li><i class="fa fa-fax"></i> Fax: {{ get_option('site_fax') }}</li>
                    <li><i class="fa fa-envelope"></i> <a href="mailto:{{ get_option('site_email') }}">{{ get_option('site_email') }}</a></li>
                </ul>
            </div><!-- .container -->
        </div><!-- .footer-contact -->

        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <div class="footer-info">
			<p>Giấy phép số 288/GP-BTTTT 10-6-2022
                        <br />
Tổng biên tập: <b> Thượng tá CAO VĂN DÂN</b>;<br />Phó tổng biên tập: <b> Thượng tá NGUYỄN TRỌNG THIẾT</b>;
</p>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="copyright">
                        <p>Bản quyền thuộc Báo HẢI QUÂN VIỆT NAM <br />
                            <span style="padding-right: 10px;">Lượt xem trang: {{ number_format(get_option('site_view'), 0, ',', '.') }} </span>
                        Xây dựng bởi: <a href="http://cnv.vn" style="color: white;">CNV.vn</a></p>
                    </div>
                </div>
            </div>
        </div><!-- .container -->
    </div><!-- .bot-footer -->
</footer><!-- site-footer -->
