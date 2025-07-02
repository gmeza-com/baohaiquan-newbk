<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
    <h3>Email form <a href="{{ get_option('site_name') }}">{{ get_option('site_name') }}"</a></h3>
    @foreach($formData->data as $field => $data)
        <p>
            <strong>
                {{ trans('custom.form.' . $field) }}:
            </strong>
            {{ $data }}
        </p>
    @endforeach

    <p>
        Created at {{ $formData->created_at }} at {{ $form->slug }}
    </p>
</body>
</html>