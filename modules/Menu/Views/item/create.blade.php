{!! Form::open(['url' => admin_route('menu.item.store', $menu->id),
    'class' => 'form-horizontal form-bordered form-validate',
    'data-callback' => 'refreshBuilder',
    'method' => 'POST'
]) !!}
    @include('menu::item.form')
{!! Form::close() !!}
