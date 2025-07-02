  @component('components.block')
    @slot('title', trans('user::language.avatar'))
    <div class="form-horizontal form-bordered">
        <div class="form-group">
            {!! Form::label('avatar', trans('user::language.avatar'), ['class' => 'col-md-3 control-label']) !!}

            <div class="col-md-6">
                <div class="col-md-4">
                    <img src="{{ $user->avatar }}" width="80" class="img-circle">
                </div>
                <div class="col-md-8">
                    <p>
                        {!! Form::file('avatar', null, ['class' => 'col-md-3 control-label']) !!}
                    </p>
                    <p>
                        <input type="checkbox" name="remove_avatar" value="1" id="remove_avatar">
                        <label for="remove_avatar">{{ trans('user::language.delete_your_avatar') }}</label>
                    </p>
                </div>
            </div>
        </div>
    </div>
  @endcomponent
