<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
<table class="wrapper" width="100%" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td align="center">
<table class="content" width="100%" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td class="header"><a href="{{ get_option('site_url') }}"> {{ get_option('site_name') }} </a></td>
</tr>
<tr>
<td class="body" width="100%" cellpadding="0" cellspacing="0">
<table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td class="content-cell">@if ($level == 'error')
<h1 id="mcetoc_1bbps393g1">Whoops!</h1>
@else
<h1 id="mcetoc_1bbps393g2">Hello !</h1>
<p>@endif</p>
<p>@foreach ($introLines as $line) {{ $line }} @endforeach @if (isset($actionText)) @php switch ($level) { case 'success': $color = 'green'; break; case 'error': $color = 'red'; break; default: $color = 'blue'; } @endphp</p>
<table class="action" align="center" width="100%" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td align="center">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td align="center">
<table border="0" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td><a href="{{ $actionUrl }}" class="button button-{{ $color or 'blue' }}" target="_blank" rel="noopener noreferrer">{{ $actionText }}</a></td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<p>@endif @foreach ($outroLines as $line) {{ $line }} @endforeach</p>
<p></p>
<p>Regards, {{ get_option('site_name') }}</p>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td>
<table class="footer" align="center" width="570" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td class="content-cell" align="center">&copy; {{ date('Y') }}&nbsp;<span>{{ get_option('site_name') }}</span>. All rights reserved.</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</body>
</html>