<div class="row">
    <div class="col-lg-2"></div>
    <div class="col-lg-6">
        @component('components.block')
            @slot('title', trans('language.basic_info'))
            <div class="block-body">
                <div class="form-bordered">
                    <div class="form-group">
                        {!! Form::label('name', trans('language.name'), ['class' => 'label-control']) !!}
                        {!! Form::text('name', @$category->name, [
                            'class' => 'form-control',
                            'required',
                        ]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('amount', trans('royalty::language.amount'), ['class' => 'label-control']) !!}
                        {!! Form::number('amount', @$category->amount, [
                            'class' => 'form-control',
                            'required',
                        ]) !!}
                    </div>
                </div>
                <div class="form-horizontal" style="padding-top: 15px">
                    <div class="form-group" style="padding: 1px; border-width: 0">
                        {!! Form::label('status', trans('language.activate'), ['class' => 'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            <label class="switch switch-primary">
                                <input type="checkbox" name="active" value="1"
                                    {{ @$category->active ? 'checked' : '' }}>
                                <span></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        @endcomponent
    </div>
</div>

@include('partial.editor')
