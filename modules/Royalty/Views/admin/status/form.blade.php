<div class="row">
    <div class="col-lg-2"></div>
    <div class="col-lg-6">
        @component('components.block')
            @slot('title', trans('language.basic_info'))
            <div class="block-body">
                <div class="form-bordered">
                    <div class="form-group">
                        {!! Form::label('name', trans('language.name'), ['class' => 'label-control']) !!}
                        {!! Form::text('name', @$royaltyStatus->name, [
                            'class' => 'form-control',
                            'required',
                        ]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('ordering', trans('language.position'), ['class' => 'label-control']) !!}
                        {!! Form::number('ordering', @$royaltyStatus->ordering, [
                            'class' => 'form-control',
                            'required',
                        ]) !!}
                    </div>
                </div>
            </div>
        @endcomponent
    </div>
</div>

@include('partial.editor')
