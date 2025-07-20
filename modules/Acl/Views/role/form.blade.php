<div class="row">
    <div class="col-lg-6">
        @component('components.block')
            @slot('title', trans('language.language'))

            <div class="block-body">
                <div class="form-horizontal form-bordered">
                    <ul class="nav nav-tabs" data-toggle="tabs">
                        @foreach (config('cnv.languages') as $language)
                            <li {{ $loop->first ? 'class=active' : '' }}>
                                <a href="#{{ $language['locale'] }}">
                                    {{ $language['name'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content">
                        @foreach (config('cnv.languages') as $language)
                            <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="{{ $language['locale'] }}">
                                <div class="form-group">
                                    {!! Form::label('title', trans('acl::language.role_name'), ['class' => 'label-control col-md-3']) !!}
                                    <div class="col-md-9">
                                        {!! Form::text('language[' . $language['locale'] . '][name]', @$role->language('name', $language['locale']), [
                                            'class' => 'form-control',
                                            'required',
                                        ]) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('description', trans('acl::language.role_description'), ['class' => 'label-control col-md-3']) !!}
                                    <div class="col-md-9">
                                        {!! Form::textarea(
                                            'language[' . $language['locale'] . '][description]',
                                            @$role->language('description', $language['locale']),
                                            ['class' => 'form-control', 'required'],
                                        ) !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endcomponent

        @component('components.block')
            @slot('title', trans('acl::language.role_create'))
            <div class="block-body">
                <div class="form-horizontal form-bordered">
                    <div class="form-group">
                        {!! Form::label('slug', trans('language.slug'), ['class' => 'col-md-3 control-label']) !!}

                        <div class="col-md-9">
                            {!! Form::text('slug', @$role->slug, ['class' => 'form-control', 'required']) !!}
                        </div>
                    </div>
                </div>
            </div>
        @endcomponent
    </div>

    <div class="col-lg-6">
        @component('components.block')
            @slot('title', trans('acl::language.role_permissions'))
            <div class="block-body">
                <div class="form-bordered">
                    <div class="form-group">
                        @foreach ($permissions as $module => $perms)
                            <h4>
                                <strong>{{ ucwords(trans('acl::language.' . $module)) }}</strong>
                            </h4>
                            <hr>
                            @foreach ($perms as $perm)
                                <p>
                                    <label class="switch switch-primary">
                                        <input type="checkbox" name="permission[]" id="permision_{{ $perm->id }}"
                                            value="{{ $perm->id }}"
                                            {{ $role->permissions->contains($perm) ? 'checked' : '' }}>
                                        <span></span>
                                    </label>
                                    <label class="switch-label"
                                        for="permision_{{ $perm->id }}">{{ $perm->language('description') }}</label>
                                </p>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        @endcomponent
    </div>
</div>
