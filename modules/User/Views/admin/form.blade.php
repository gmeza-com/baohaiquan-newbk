<div class="row">
    <div class="col-lg-7 col-md-12">
        @component('components.block')
            @slot('title', trans('user::language.user_create'))
            <div class="form-horizontal form-bordered">
                @include('user::form.basic')

                <div class="form-group">
                    {!! Form::label('role', trans('user::language.roles'), ['class' => 'col-md-3 control-label']) !!}

                    <div class="col-md-6">
                        @foreach ($roles as $role)
                            <p>
                                <label class="switch switch-primary">
                                    <input type="checkbox" name="role[]" id="role_{{ $role->id }}"
                                        value="{{ $role->id }}" {{ $user->roles->contains($role) ? 'checked' : '' }}>
                                    <span></span>
                                </label>
                                <label class="switch-label"
                                    for="permision_{{ $role->id }}">{{ $role->language('name') }}</label>
                            </p>
                        @endforeach
                        <div class="clearfix"></div>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('activated', trans('language.status'), ['class' => 'col-md-3 control-label']) !!}

                    <div class="col-md-6">
                        <label class="switch switch-success">
                            <input type="checkbox" name="activated" value="1" {{ $user->activated ? 'checked' : '' }}>
                            <span></span>
                        </label>
                    </div>
                </div>
            </div>
        @endcomponent
    </div>

    <div class="col-lg-5 col-md-12">
        @foreach ($user_settings_fields as $field)
            @include($field)
        @endforeach
    </div>
</div>
