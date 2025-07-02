@extends('admin')

@section('page_header')

@can('menu.menu.create')
<div class="pull-right">
  <a href="{{ admin_route('menu.create') }}" class="btn btn-primary">
    <i class="fa fa-plus"></i> {{ trans('language.create') }}
  </a>
</div>
@endcan

<h1>
  {{ $title }}
</h1>
@stop

@section('content')

@component('components.block')
@slot('title', trans('menu::language.menu_list'))

<div class="block-body">
  <div class="row">
    {!! Form::open(['url' => admin_route('menu.index'), 'method' => 'GET', 'id' => 'choose-menu']) !!}
    <div class="col-md-4">
      <select name="menu" id="menus-list" class="form-control">
        <option disabled selected>{{ trans('menu::language.choose_your_menu') }}</option>
        @foreach($menus as $item)
        <option {{ Request::get('menu')  === $item ? 'selected' : '' }} value="{{ $item }}">{{ $item }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2">

      @if(isset($menu))
      @can('menu.menu.edit')
      <a href="{{ admin_route('menu.edit', $menu->id) }}" class="btn btn-info">
        <i class="fa fa-pencil"></i> {{ trans('language.edit') }}
      </a>
      @endcan

      @can('menu.menu.destroy')
      {!! Form::button(trans('language.delete'), [
      'type' => 'button',
      'class' => 'btn btn-danger',
      'data-url' => admin_route('menu.destroy', $menu->id),
      'data-action' => 'confirm_to_delete',
      'data-message' => trans('language.confirm_to_delete'),
      'data-callback' => 'redirect_to'
      ]) !!}
      @endcan
      @endif
    </div>
    {!! Form::close() !!}
  </div>
</div>
@endcomponent

@include('menu::builder')

@stop

@push('footer')
<script src="/assets/js/jquery.nestable.js"></script>
<script src="/assets/js/pages/menu.js"></script>
@if(isset($menu))
<script>
  reloadBuilderContent(<?php echo $menu->id ?>);
</script>
@endif
@endpush