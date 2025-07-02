@extends('admin')

@section('page_header')
<h1>
  {{ $title }}
</h1>
@stop

@section('content')

@component('components.block')

@slot('title', $title)

<div class="block-body">
  @foreach($moduleTemplates as $module => $emails)
  <div class="row">
    <div class="col-md-3">
      <h4>
        {{ ucfirst($module) }}
      </h4>
    </div>
    <div class="col-md-9">
      <div class="list-group">
        @foreach($emails as $slug => $template)
        <a href="{{ admin_url('option/email/' . $module . '/' . $slug) }}" class="list-group-item">{{ trans($template['title']) }}</a>
        @endforeach
      </div>
    </div>
  </div>
  @endforeach
</div>

@endcomponent

@stop