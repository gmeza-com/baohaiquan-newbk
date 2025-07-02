{!! Form::open([
    'url' => admin_route('menu.item.update', ['menu' => $menu->id, 'item' => $item->id]),
    'class' => 'form-horizontal form-bordered form-validate',
    'data-callback' => 'refreshBuilder',
    'method' => 'put'
]) !!}
    @include('menu::item.form')
{!! Form::close() !!}
