<!DOCTYPE html>
<html>
<head>
</head>
<body>
<table class="wrapper" width="100%" cellpadding="0" cellspacing="0">
    <tbody>
    <tr>
        <td align="center">
            <table class="content" width="100%" cellpadding="0" cellspacing="0">
                <tbody>
                <tr>
                    <td class="header"><a href="/iadmin/option/email/system/{{ get_option('site_url') }}"> {{ get_option('site_name') }} </a></td>
                </tr>
                <tr>
                    <td class="body" width="100%" cellpadding="0" cellspacing="0">
                        <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0">
                            <tbody>
                            <tr>
                                <td class="content-cell">
                                    <p>Thư liên hệ từ {{ get_option('site_name') }}</p>
                                    <hr />
                                    <p>Tiêu đề: {{ $title }}</p>
                                    <p>Tên người gửi: {{ $name }}</p>
                                    <p>Email: {{ $email }}</p>
                                    <p>Nội dung:</p>
                                    <p>{!! $content !!}</p>
                                    <p></p>
                                    <p>{{ get_option('site_name') }}</p>
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
                                <td class="content-cell" align="center">© {{ date('Y') }} <span>{{ get_option('site_name') }}</span>. All rights reserved.</td>
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