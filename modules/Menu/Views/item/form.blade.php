
<!-- Languages -->
<ul class="nav nav-tabs" data-toggle="tabs">
    @foreach(config('cnv.languages') as $language)
        <li {{ $loop->first ? 'class=active' : '' }}>
            <a href="#{{ $language['locale'] }}">
                {{ $language['name'] }}
            </a>
        </li>
    @endforeach
</ul>

<div class="tab-content">
    @foreach(config('cnv.languages') as $language)
        <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="{{ $language['locale'] }}">
            <div class="form-group">
                {!! Form::label('title', trans('menu::language.menu_title'), ['class' => 'label-control col-md-4']) !!}
                <div class="col-md-8">
                    {!! Form::text('language['. $language['locale'] .'][name]', @$item->language('name', $language['locale']), ['class' => 'form-control', 'required']) !!}
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="form-group">
    {!! Form::label('attributes[url]', trans('menu::language.menu_url'), ['class' => 'label-control col-md-4']) !!}
    <div class="col-md-8">
        <div class="input-group">
            {!! Form::text('attributes[url]', @$item->attributes['url'], ['class' => 'form-control', 'required']) !!}
            <span class="input-group-btn">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#search-links"><i class="fa fa-search"></i></button>
            </span>
        </div>
    </div>
</div>

@include('menu::item.dropdown_menu')

<!-- Thuộc tính -->
<div class="form-group">
    {!! Form::label('attributes[id]', trans('menu::language.menu_attribute.id'), ['class' => 'label-control col-md-4']) !!}
    <div class="col-md-8">
        {!! Form::text('attributes[id]', @$item->attributes['id'], ['class' => 'form-control']) !!}
        <span class="help-block">{{ trans('language.option') }}</span>
    </div>
</div>

<div class="form-group">
    {!! Form::label('attributes[class]', trans('menu::language.menu_attribute.class'), ['class' => 'label-control col-md-4']) !!}
    <div class="col-md-8">
        {!! Form::text('attributes[class]', @$item->attributes['class'], ['class' => 'form-control']) !!}
        <span class="help-block">{{ trans('language.option') }}</span>
    </div>
</div>

<div class="form-group">
    {!! Form::label('attributes[rel]', trans('menu::language.menu_attribute.rel'), ['class' => 'label-control col-md-4']) !!}
    <div class="col-md-8">
        {!! Form::select('attributes[rel]', ['dofollow'=>'dofollow', 'nofollow'=>'nofollow'], @$item->attributes['rel'], ['class' => 'form-control']) !!}
        <span class="help-block">{{ trans('language.option') }}</span>
    </div>
</div>

<div class="form-group">
    {!! Form::label('attributes[icon]', trans('menu::language.menu_attribute.icon'), ['class' => 'label-control col-md-4']) !!}
    <div class="col-md-8">
        {!! Form::text('attributes[icon]', @$item->attributes['icon'], ['class' => 'form-control']) !!}
        <span class="help-block">{{ trans('language.option') }}</span>
    </div>
</div>

<div class="form-group">
    {!! Form::label('attributes[target]', trans('menu::language.menu_attribute.target'), ['class' => 'label-control col-md-4']) !!}
    <div class="col-md-8">
        {!! Form::select('attributes[target]', ['_self'=>'_self', '_blank'=>'_blank'], @$item->attributes['target'], ['class' => 'form-control']) !!}
        <span class="help-block">{{ trans('language.option') }}</span>
    </div>
</div>

@if(auth()->user()->is_super_admin)
<div class="form-group">
    {!! Form::label('attributes[permission]', trans('menu::language.menu_attribute.permission'), ['class' => 'label-control col-md-4']) !!}
    <div class="col-md-8">
        {!! Form::text('attributes[permission]', @$item->attributes['permission'], ['class' => 'form-control', 'required']) !!}
        <span class="help-block">{!! trans('menu::language.menu_attribute.permission_help') !!}</span>
    </div>
</div>
@else
    {!! Form::hidden('attributes[permission]', @$item->attributes['permission'] ?: '*') !!}
@endif

<div class="form-group form-actions">
    <div class="col-md-8 col-md-offset-4">
        {!! Form::button(trans('language.ok'), ['class' => 'btn btn-primary', 'type' => 'submit']) !!}
        {!! Form::button(trans('language.cancel'), ['class' => 'btn btn-default', 'type' => 'button', 'id' => 'cancel_button']) !!}
    </div>
</div>
