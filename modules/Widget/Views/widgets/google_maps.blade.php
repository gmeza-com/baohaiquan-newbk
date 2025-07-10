@component('components.block')
    @slot('title', 'Google Maps')
    <div class="block-body">
        <div class="form-bordered">

            <div class="form-group">
                {!! Form::label('title', trans('widget::language.widget_google_title'), ['class' => 'label-control']) !!}
                {!! Form::text('setting[title]', @$widget->setting['title'], ['class' => 'form-control', 'required']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('description', trans('widget::language.widget_google_description'), [
                    'class' => 'label-control',
                ]) !!}
                {!! Form::text('setting[description]', @$widget->setting['description'], [
                    'class' => 'form-control',
                    'required',
                ]) !!}
            </div>

            <div class="form-group">
                {!! Form::label('lat', trans('widget::language.widget_google_lat'), ['class' => 'label-control']) !!}
                {!! Form::text('setting[lat]', @$widget->setting['lat'], ['class' => 'form-control', 'required']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('lng', trans('widget::language.widget_google_lng'), ['class' => 'label-control']) !!}
                {!! Form::text('setting[lng]', @$widget->setting['lng'], ['class' => 'form-control', 'required']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('icon', trans('widget::language.widget_google_icon'), ['class' => 'label-control']) !!}

                <div class="input-group">
                    {!! Form::text('setting[icon]', @$widget->setting['icon'], [
                        'class' => 'form-control',
                        'required',
                        'id' => 'map-icon',
                    ]) !!}
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-primary" data-toggle="files" data-target="map-icon"><i
                                class="fa fa-picture-o"></i></button>
                    </span>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('zoom', trans('widget::language.widget_google_zoom'), ['class' => 'label-control']) !!}
                {!! Form::text('setting[zoom]', @$widget->setting['zoom'] ?: 17, ['class' => 'form-control', 'required']) !!}
            </div>

        </div>
    </div>
@endcomponent
