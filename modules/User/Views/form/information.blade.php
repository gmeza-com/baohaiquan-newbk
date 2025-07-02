@component('components.block')
    @slot('title', trans('user::language.information'))
    <div class="form-horizontal form-bordered">
        <div class="form-group">
            {!! Form::label('setting[birthday]', trans('user::language.birthday'), ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-6">
                {!! Form::text('setting[birthday]', $user->getSetting('birthday'), ['class' => 'form-control input-datepicker', 'data-date-format' => 'dd/mm/yyyy', 'placeholder' => 'dd/mm/yyyy']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('setting[address]', trans('user::language.address'), ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-6">
                {!! Form::text('setting[address]', $user->getSetting('address'), ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('setting[job]', trans('user::language.job'), ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-6">
                {!! Form::text('setting[job]', $user->getSetting('job'), ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('setting[web]', trans('user::language.web'), ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-6">
                {!! Form::text('setting[web]', $user->getSetting('web'), ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('setting[phone]', trans('user::language.phone'), ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-6">
                {!! Form::text('setting[phone]', $user->getSetting('phone'), ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('setting[favorite]', trans('user::language.favorite'), ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-6">
                {!! Form::textarea('setting[favorite]', $user->getSetting('favorite'), ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('setting[about]', trans('user::language.about'), ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-6">
                {!! Form::textarea('setting[about]', $user->getSetting('about'), ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>
@endcomponent
