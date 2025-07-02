@if($items->count())
    @can('menu.item.index')
        <div class="dd" id="nestable">
            <ol class="dd-list">
                @include('menu::item.item', ['items' => $items->filter(function($item, $key) { return $item->parent_id == 0; })])
            </ol>
        </div>
    @endcan
@endif
