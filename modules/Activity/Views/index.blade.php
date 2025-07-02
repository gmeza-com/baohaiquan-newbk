@extends('admin')

@section('page_header')

<div class="pull-right">
  {!! Form::button('<i class="fa fa-trash-o"></i> Clear', [
  'type' => 'button',
  'class' => 'btn btn-danger',
  'data-url' => admin_route('activity.index'),
  'data-action' => 'confirm_to_delete',
  'data-message' => trans('language.confirm_to_delete'),
  'data-callback' => 'reload_page'
  ]) !!}
</div>

<h1>
  {{ $title }}
</h1>
@stop

@section('content')

<div class="block">
  <!-- Tracker Title -->
  <div class="block-title">
    <h4>{{ $title }}</h4>
  </div>
  <!-- END Tracker Title -->

  <!-- Tracker Content -->
  <div class="block-body">
    <div class="block-content-full">
      <!-- Issues -->
      <table class="table table-borderless table-striped table-vcenter" style="font-size: 0.9rem;">
        <thead>
          <tr>
            <td colspan="2">
              <ul class="list-inline remove-margin">
                <li>
                  <i class="fa fa-check"></i>
                  <a href="javascript:void(0)" class="text-success"><strong>{{ trans('activity::language.count_logs', compact('count_activities')) }}</strong></a>
                </li>
              </ul>
            </td>
          </tr>
        </thead>
        <tbody>
          @foreach($activities as $activity)
          <tr>
            <td class="text-center" style="width: 60px;">
              <a href="javascript:void(0)" class="text-success">
                <i class="fa fa-exclamation-circle fa-2x"></i>
              </a>
            </td>
            <td>
              <span class="{{ $activity->class }}">{{ ucfirst($activity->event) }}</span>
              <strong>{!! $activity->description !!}</strong>
              by <strong>{{ $activity->creator }}</strong>
              <div class="text-muted">
                #{{ $activity->id }} created at {{ $activity->created_at }} - <span class="label label-warning">{{ $activity->ip }}</span><br>
                {{ $activity->user_agent }}
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <!-- END Issues -->

      <!-- Pagination -->
      <div class="text-center">
        {!! $activities->links() !!}
      </div>
      <!-- END Pagination -->
    </div>
  </div>
  <!-- END Tracker Content -->
</div>

@stop