<div class="form-group">
    {!! Form::label('slug', trans('language.slug'), ['class' => 'col-md-3 control-label']) !!}

    <div class="col-md-9">
        {!! Form::text('slug', $menu->slug, ['class' => 'form-control', 'required']) !!}
    </div>
</div>

<div class="form-group form-actions">
    <div class="col-md-9 col-md-offset-3">
        {!! Form::button(trans('language.ok'), ['class' => 'btn btn-primary', 'type' => 'submit']) !!}
    </div>
</div>
