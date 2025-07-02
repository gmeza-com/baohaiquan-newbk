<!-- Main Sidebar -->
<div id="sidebar">
    <!-- Wrapper for scrolling functionality -->
    <div id="sidebar-scroll">
        <!-- Sidebar Content -->
        <div class="sidebar-content">
            <!-- Brand -->
            <a href="{{ url(admin_path()) }}" class="sidebar-brand">
                <i class="gi gi-flash"></i><span class="sidebar-nav-mini-hide">
                    <img src="/assets/images/logo_cnv.png" alt="cnvcms" width="60">
                </span>
            </a>
            <!-- END Brand -->

            <!-- Sidebar Navigation -->
            <ul class="sidebar-nav">
                @foreach($menu_items as $item)
                    @php
                        if($item->children->count()) {
                            $class = 'class="sidebar-nav-menu"';
                        } else {
                            $class = '';
                        }
                    @endphp
                    @if($item->attributes['permission'] === '*' || ($item->attributes['permission'] !== '*' && allow($item->attributes['permission'])))
                    <li>
                        <a href="{{ @$item->attributes['url'] == '#' ? 'javascript:void(0);' :  @$item->attributes['url'] }}" {!! $class !!}>
                            @if($item->children->count())
                                <i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i>
                            @endif
                            @if(isset($item->attributes['icon']))
                                <i class="{{ @$item->attributes['icon'] }} sidebar-nav-icon"></i>
                            @endif
                            <span class="sidebar-nav-mini-hide">{{ @$item->language('name') }}</span>
                        </a>
                        @if($item->children->count())
                            <ul>
                                @foreach($item->children->sortBy('position')  as $child)
                                    @if($child->attributes['permission'] === '*' || ($child->attributes['permission'] !== '*' && allow($child->attributes['permission'])))
                                        <li>
                                            <a href="{{ @$child->attributes['url'] }}">
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
