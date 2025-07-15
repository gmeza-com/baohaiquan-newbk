<meta name="gallery-type" content="{{ @$gallery->type }}">

@php
    $year = date('Y');
    $month = date('m');
@endphp
<div class="row">
    <div class="col-lg-8">
        @component('components.block')
            @slot('title', trans('language.basic_info'))
            <div class="block-body">
                <div class="form-bordered">
                    <div class="form-group">
                        {!! Form::label('user_id', trans('royalty::language.claim_for'), [
                            'class' => 'control-label',
                        ]) !!}
                        {!! Form::select(
                            'user_id',
                            get_list_authors_for_choose(),
                            isset($royalty) && $royalty->user_id ? $royalty->user_id : auth()->user()->id,
                            [
                                'class' => 'form-control',
                                'required',
                            ],
                        ) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('amount', trans('royalty::language.amount'), ['class' => 'label-control']) !!}
                        {!! Form::number('amount', @$royalty->amount, [
                            'class' => 'form-control',
                            'required',
                        ]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('description', trans('royalty::language.note'), ['class' => 'label-control']) !!}
                        {!! Form::textarea('note', @$royalty->note, [
                            'class' => 'form-control',
                            'rows' => 3,
                        ]) !!}
                    </div>
                </div>
            </div>
        @endcomponent

    </div>
    <div class="col-lg-4">
        @component('components.block')
            @slot('title', trans('language.setting_field'))
            <div class="block-body">
                <div class="form-horizontal form-bordered">
                    <div class="form-group">
                        {!! Form::label('category_id', trans('royalty::language.category'), [
                            'class' => 'control-label col-md-4',
                        ]) !!}
                        <div class="col-md-8">
                            {!! Form::select(
                                'category_id',
                                get_list_royalty_category_to_choose(),
                                isset($royalty) && $royalty->category_id ? $royalty->category_id : 0,
                                [
                                    'required' => true,
                                    'class' => 'form-control',
                                    'style' => 'width: 100%!important',
                                ],
                            ) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('status_id', trans('royalty::language.status'), [
                            'class' => 'control-label col-md-4',
                        ]) !!}
                        <div class="col-md-8">
                            {!! Form::select(
                                'status_id',
                                get_all_royalty_status_to_choose(),
                                isset($royalty) && $royalty->status_id ? $royalty->status_id : 1,
                                [
                                    'required' => true,
                                    'class' => 'form-control',
                                    'style' => 'width: 100%!important',
                                ],
                            ) !!}
                        </div>
                    </div>

                </div>
            </div>
        @endcomponent
        @component('components.block')
            @slot('title', trans('royalty::language.claim_month'))
            <div class="block-body">
                <div class="row" style="margin-bottom: 5px; min-width: 240px">
                    <div class="col-lg-6" style="margin-bottom: 10px">
                        <label for="input-year">Năm</label>
                        <select id="input-year" class="form-control non-select2" name="year">
                            @for ($i = date('Y'); $i >= 2024; $i--)
                                <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-lg-6" style="margin-bottom: 10px">
                        <label for="input-month">Tháng</label>
                        <select id="input-month" class="form-control non-select2" name="month">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i < 10 ? '0' . $i : $i }}" {{ $i == $month ? 'selected' : '' }}>
                                    {{ $i < 10 ? '0' . $i : $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
        @endcomponent
    </div>

    @push('footer')
        <script>
            "use strict";
            (function($) {

            })(jQuery);
        </script>
    @endpush
