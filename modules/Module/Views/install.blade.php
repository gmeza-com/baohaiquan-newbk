@extends('admin')

@section('page_header')
    <h1>
        <i class="fa fa-circle-o"></i> {{ $title }}
    </h1>
@stop

@section('content')

    @component('components.block')
        @slot('title', $title)

        {!! Form::open([
            'url' => admin_url('module/install'),
            'method' => 'GET',
            'class' => 'form-validate',
            'id' => 'save',
            'data-callback' => 'redirect_to'
        ]) !!}

        <div class="text-center">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="form-group">
                        <input type="text" name="secret_key" class="form-control" placeholder="{{ trans('module::language.input_key_to_install_or_update') }}">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-alt btn-primary">
                            {{ trans('module::language.install') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

    @endcomponent

@stop

@push('footer')
<script src="/assets/js/pages/module.js"></script>
@endpush