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
                                    {!! Form::label('description', trans('acl::language.permission_description'), [
                                        'class' => 'label-control col-md-3',
                                    ]) !!}
                                    <div class="col-md-9">
                                        {!! Form::textarea(
                                            'language[' . $language['locale'] . '][description]',
                                            @$permission->language('description', $language['locale']),
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
    </div>
    <div class="col-lg-6">
        @component('components.block')
            @slot('title', trans('acl::language.permission_create'))
            <div class="block-body">
                <div class="form-horizontal form-bordered">
                    <div class="form-group">
                        {!! Form::label('permission_module', trans('acl::language.permission_module'), [
                            'class' => 'col-md-3 control-label',
                        ]) !!}

                        <div class="col-md-9">
                            {!! Form::text('module', @$permission->module, ['class' => 'form-control', 'required']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('slug', trans('language.slug'), ['class' => 'col-md-3 control-label']) !!}

                        <div class="col-md-9">
                            {!! Form::text('slug', @$permission->slug, ['class' => 'form-control', 'required']) !!}
                        </div>
                    </div>
                </div>
            </div>
        @endcomponent
    </div>
</div>
