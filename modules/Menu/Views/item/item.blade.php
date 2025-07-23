@foreach ($items->sortBy('position') as $item)
    <li class="dd-item dd3-item" data-id="{{ $item->id }}">
        <div class="dd-handle dd3-handle"></div>
        <div class="dd3-content">
            <div class="pull-right">
                @can('menu.item.edit')
                    {!! Form::button(trans('language.edit'), [
                        'class' => 'btn btn-primary btn-xs',
                        'onclick' => 'editItem(' . $item->menu_id . ', ' . $item->id . ')',
                    ]) !!}
                @endcan
                @can('menu.item.destroy')
                    {!! Form::button(trans('language.delete'), [
                        'type' => 'button',
                        'class' => 'btn btn-xs btn-danger',
                        'data-url' => admin_route('menu.item.destroy', ['menu' => $item->menu_id, 'item' => $item->id]),
                        'data-action' => 'confirm_to_delete',
                        'data-message' => trans('language.confirm_to_delete'),
                        'data-callback' => 'refreshBuilder',
                    ]) !!}
                @endcan
            </div>
            {{ $item->language('name') }}
        </div>
        @if ($item->children->count())
            <ol class="dd-list">
                @include('menu::item.item', ['items' => $item->children])
            </ol>
        @endif
    </li>
@endforeach
