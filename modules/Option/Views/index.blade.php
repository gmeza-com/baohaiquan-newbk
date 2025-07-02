@extends('admin')

@section('page_header')

    <div class="pull-right">
        <button type="button" class="btn btn-primary" onclick="submitForm('#save');">
            <i class="fa fa-save"></i> {{ trans('language.save') }}
        </button>
    </div>

    <h1>
        {{ $title }}
    </h1>
@stop

@section('content')

    @component('components.block')

       @slot('title', $title)

        {!! Form::open([
            'url' => admin_url('option'),
            'method' => 'POST',
            'class' => 'form-validate form-horizontal',
            'id' => 'save',
            'enctype' => 'multipart/form-data',
            'data-callback' => 'reload_page'
        ]) !!}
            @foreach($options as $option)
                <div class="form-group">
                    {!! Form::label($option->name, $option->name, ['class' => 'control-label col-md-3']) !!}
                    <div class="col-md-6">
                        {!! Form::text($option->name, $option->value, ['class' => 'form-control']) !!}
                    </div>
                </div>
            @endforeach
        {!! Form::close() !!}

    @endcomponent

@stop
