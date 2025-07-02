<p>
    <code>@{{ get_option('site_name') }}</code> {{ trans('option::language.email_guide_notify_site_name') }}
</p>
<p>
    <code>@{{ get_option('site_url') }}</code> {{ trans('option::language.email_guide_notify_site_url') }}
</p>
<p>
    <code>@{{ $actionText }}</code> {{ trans('option::language.email_guide_notify_action_text') }}
</p>
<p>
    <code>@{{ $actionUrl }}</code> {{ trans('option::language.email_guide_notify_action_url') }}
</p>
<p>
    <code>@{{ $level }}</code> {!! trans('option::language.email_guide_notify_level')  !!}
</p>
<p>
    <code>@{{ $introLines }}</code> {{ trans('option::language.email_guide_notify_intro_line') }}
</p>
<p>
    <code>@{{ $outroLines }}</code> {{ trans('option::language.email_guide_notify_outro_line') }}
</p>
<p>
    <code>@{{ date('Y') }}</code> {{ trans('option::language.email_guide_notify_year') }}
</p>