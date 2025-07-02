<div class="form-group">
    {!! Form::label('name', trans('user::language.name'), ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('name', $user->name, ['class' => 'form-control', 'required']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('username', trans('user::language.username'), ['class' => 'col-md-3 control-label']) !!}

    <div class="col-md-6">
        {!! Form::text('username', @$user->username, ['class' => 'form-control', 'required']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('email', trans('user::language.email'), ['class' => 'col-md-3 control-label']) !!}

    <div class="col-md-6">
        {!! Form::text('email', @$user->email, ['class' => 'form-control', 'required', 'data-rule-email' => true]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('password', trans('user::language.password'), ['class' => 'col-md-3 control-label']) !!}

    <div class="col-md-6">
        {!! Form::password('password', ['class' => 'form-control']) !!}
        <p class="help-block">{{ trans('user::language.empty_not_change') }}</p>
    </div>
</div>

<div class="form-group">
    {!! Form::label('password_confirmation', trans('user::language.password_confirmation'), ['class' => 'col-md-3 control-label', 'id' => 'password']) !!}

    <div class="col-md-6">
        {!! Form::password('password_confirmation', ['class' => 'form-control', 'data-rule-equalTo' => '#password']) !!}
    </div>
</div>
