<ul class="{{ $class }}">
    <!--<li class="search">
        <form action="/blogs/search">
        <input type="text" class="search-field" placeholder="Nhập từ khoá tìm kiếm" name="q">
        <div class="button1 button-search-mobile" id="searchSubmit1">
            <button type="submit">
                <i class="fa fa-search"></i>
            </button>
        </div>
        </form>
    </li>-->
    @foreach($menus as $item)
        <li>
            <a href="{{ @$item->attributes['url'] == '#' ? 'javascript:void(0);' :  @$item->attributes['url'] }}" {!! @$item->attributes_html !!}>
                @if(@$item->attributes['icon'])
                    <i class="{{ @$item->attributes['icon'] }}"></i>
                    <span class="sr-only">{{ @$item->language('name') }}</span>
                @else
                    {{ @$item->language('name') }}
                @endif
            </a>
            @if($item->children->count())
                @include('theme::partial.menu', ['menus' => $item->children, 'class' => 'sub-menu'])
            @endif
        </li>
    @endforeach
</ul>
