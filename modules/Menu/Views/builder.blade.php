@if(isset($menu))

    <div class="row">
        <div class="col-md-4">
        @can('menu.item.create')
            @component('components.block')
                @slot('title', trans('menu::language.create_or_edit_menu'))
                @slot('footer', true)

                <div class="text-center" id="create-block">
                    <button class="btn btn-primary" id="create_new_item" data-id="{{ $menu->id }}">
                        <i class="fa fa-plus"></i> {{ trans('language.create')}}
                    </button>
                </div>

                <div id="form" hidden="true"></div>
            @endcomponent
        @endcan
        </div>


        @can('menu.item.index')
        <div class="col-md-8">
            @component('components.block')
                @slot('title', trans('menu::language.builder'))
                @slot('action')
                    @can('menu.item.sort')
                        <button type="button" class="btn btn-xs btn-success" id="save-position" onclick="savePositionAllItems({{ $menu->id }});">
                            <i class="fa fa-refresh"></i> {{ trans('language.save') }}
                        </button>
                    @endcan
                @endslot
                <div id="content">...</div>

                <div class="clearfix"></div>
            @endcomponent
        </div>
        @endcan
    </div>

@else
    @component('components.alert')
        @slot('type', 'info')
        {{ trans('menu::language.please_choose_one_menu') }}</p>
    @endcomponent
@endif

@include('menu::item.modal_search')
