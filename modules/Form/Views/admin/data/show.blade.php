@extends('admin')

@section('page_header')
    <div class="pull-right">
        <a href="{{ admin_url('form/data') }}" class="btn btn-default">
            <i class="fa fa-arrow-circle-left"></i> {{ trans('language.back') }}
        </a>
    </div>
    <h1>
        {{ $title }}
    </h1>
@stop

@section('content')

    @component('components.block')

        @slot('title', trans('form::language.show_data'))

        <table class="table table-hover table-striped">
            @foreach($formData->data as $field => $data)
                <tr>
                    <td style="width: 20%">
                        {{ trans('custom.form.' . $field) }}
                    </td>
                    <td>
                        {{ $data }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <td>
                    {{ trans('language.created_at') }}
                </td>
                <td>
                    {{ $formData->created_at }}
                </td>
            </tr>
            <tr>
                <td>
                    {{ trans('language.updated_at') }}
                </td>
                <td>
                    {{ $formData->updated_at }}
                </td>
            </tr>
            <tr>
                <td>
                    Type
                </td>
                <td>
                    <span class="label label-success">
                        {{ $formData->form->slug }}
                    </span>
                </td>
            </tr>
        </table>
    @endcomponent

@stop