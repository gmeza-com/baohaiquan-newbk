<!-- Main Sidebar -->
@php
    use Illuminate\Support\Str;
    $currentUrl = request()->url();
    $fullUrl = request()->fullUrl();
    $root = request()->root();
@endphp

<div id="sidebar">
    <!-- Wrapper for scrolling functionality -->
    <div id="sidebar-scroll">
        <!-- Sidebar Content -->
        <div class="sidebar-content">
            <!-- Brand -->
            <a href="{{ url(admin_path()) }}" class="sidebar-brand">
                <span class="sidebar-nav-mini-hide">
                    <img src="/assets/images/bytedata-white-logo-vertical.webp" alt="cnvcms" height="32">
                </span>
            </a>
            <!-- END Brand -->

            <!-- Sidebar Navigation -->
            <ul class="sidebar-nav">
                @foreach ($menu_items as $item)
                    @php
                        $class = $item->children->count() ? 'sidebar-nav-menu' : '';
                        $isOpen = false;

                        // check if any of the children url attributes contains the current URL
                        if (
                            $item->children->count() &&
                            $item->children->contains(function ($child) use ($currentUrl) {
                                return strpos($currentUrl, @$child->attributes['url']) !== false;
                            })
                        ) {
                            $class .= ' open';
                            $isOpen = true;
                        }
                    @endphp
                    @if (
                        $item->attributes['permission'] === '*' ||
                            ($item->attributes['permission'] !== '*' && allow($item->attributes['permission'])) ||
                            ($child->attributes['permission'] == 'news.post.approved_level_*' &&
                                (allow('news.post.approved_level_1') ||
                                    allow('news.post.approved_level_2') ||
                                    allow('news.post.approved_level_3'))) ||
                            ($child->attributes['permission'] == 'gallery.gallery.approved_level_*' &&
                                (allow('gallery.gallery.approved_level_1') ||
                                    allow('gallery.gallery.approved_level_2') ||
                                    allow('gallery.gallery.approved_level_3'))))
                        <li>
                            <a href="{{ @$item->attributes['url'] == '#' ? 'javascript:void(0);' : @$item->attributes['url'] }}"
                                class="{!! $class !!}">
                                @if ($item->children->count())
                                    <i class="fa fa-angle-right sidebar-nav-indicator sidebar-nav-mini-hide"></i>
                                @endif
                                @if (isset($item->attributes['icon']))
                                    <i class="{{ @$item->attributes['icon'] }} sidebar-nav-icon"></i>
                                @endif
                                <span class="sidebar-nav-mini-hide">{{ @$item->language('name') }}</span>
                            </a>
                            @if ($item->children->count())
                                <ul style="{{ $isOpen ? 'display:block' : '' }}"">
                                    @foreach ($item->children->sortBy('position') as $child)
                                        @php
                                            $active =
                                                Str::before($fullUrl, '?') == $root . $child->attributes['url']
                                                    ? 'active'
                                                    : '';
                                        @endphp
                                        @if (
                                            $child->attributes['permission'] === '*' ||
                                                ($child->attributes['permission'] !== '*' && allow($child->attributes['permission'])) ||
                                                ($child->attributes['permission'] == 'news.post.approved_level_*' &&
                                                    (allow('news.post.approved_level_1') ||
                                                        allow('news.post.approved_level_2') ||
                                                        allow('news.post.approved_level_3'))) ||
                                                ($child->attributes['permission'] == 'gallery.gallery.approved_level_*' &&
                                                    (allow('gallery.gallery.approved_level_1') ||
                                                        allow('gallery.gallery.approved_level_2') ||
                                                        allow('gallery.gallery.approved_level_3'))))
                                            <li>
                                                <a href="{{ @$child->attributes['url'] }}" class="{{ $active }}">
                                                    {{ @$child->language('name') }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
        <!-- END Sidebar Content -->
    </div>
    <!-- END Wrapper for scrolling functionality -->
</div>
<!-- END Main Sidebar -->
