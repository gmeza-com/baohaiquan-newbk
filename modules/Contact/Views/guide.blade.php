<p>
    <code>@{{ $name }}</code> {{ trans('contact::web.contact_name') }}
</p>
<p>
    <code>@{{ $email }}</code> {{ trans('contact::web.contact_email') }}
</p>
<p>
    <code>@{{ $title }}</code> {{ trans('contact::web.contact_subject') }}
</p>
<p>
    <code>@{{ $content }}</code> {{ trans('contact::web.contact_message') }}
</p>
<p>
    <code>@{{ get_option('site_name') }}</code> {{ trans('option::language.email_guide_notify_site_name') }}
</p>
<p>
    <code>@{{ get_option('site_url') }}</code> {{ trans('option::language.email_guide_notify_site_url') }}
</p>
<p>
    <code>@{{ date('Y') }}</code> {{ trans('option::language.email_guide_notify_year') }}
</p>