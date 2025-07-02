<div class="alert alert-{{ $type }} alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>

    <h4>
        @if($type == 'success')
            <i class="fa fa-check-circle"></i> {{ trans('language.success') }}
        @elseif($type == 'info')
            <i class="fa fa-info-circle"></i> {{ trans('language.infor') }}
        @elseif($type == 'warning')
            <i class="fa fa-exclamation-circle"></i> {{ trans('language.warning') }}
        @else
            <i class="fa fa-check-circle"></i> {{ trans('language.error') }}
        @endif
    </h4>

    {{ $slot }}
</div>