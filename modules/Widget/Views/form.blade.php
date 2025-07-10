<div class="row">
    <div class="col-lg-8">
        @if (!$widget->type)
            @component('components.block')
                @slot('title', trans('language.basic_info'))
                <div class="block-body">
                    <div class="form-bordered">
                        <div class="form-group">
                            {!! Form::select('type', $widgetTypes, $widget->type, ['class' => 'form-control', 'required']) !!}
                        </div>
                    </div>
                </div>
            @endcomponent
        @else
            <meta name="widget_type" content="{{ $widget->type }}">
        @endif

        <div id="form-widget"></div>
    </div>

    <div class="col-lg-4">
        @component('components.block')
            @slot('title', trans('language.setting_field'))

            <div class="block-body">
                <div class="form-horizontal form-bordered">
                    <div class="form-group">
                        {!! Form::label('name', trans('widget::language.widget_name'), ['class' => 'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::text('name', $widget->name, ['class' => 'form-control', 'required']) !!}
                        </div>
                    </div>

                    @if (!$widget->slug)
                        <div class="form-group">
                            {!! Form::label('slug', trans('widget::language.widget_slug'), ['class' => 'control-label col-md-4']) !!}
                            <div class="col-md-8">
                                {!! Form::text('slug', $widget->slug, ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                    @endif

                    <div class="form-group">
                        {!! Form::label('published', trans('language.published'), ['class' => 'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            <label class="switch switch-primary">
                                <input type="checkbox" name="published" value="1"
                                    {{ @$widget->published ? 'checked' : '' }}>
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

@push('footer')
    <script>
        "use strict";
        (function($) {
            var loadFormWidget = function($type) {
                $.get('{{ request()->fullUrl() }}?type=' + $type, function($data) {
                    $('#form-widget').html($data);
                    editor().init();
                    Main().init();
                });
            };

            $(document).ready(function() {
                var defaultWidget = $('select[name=type]').val();
                if ($('select[name=type]').length > 0) {
                    loadFormWidget(defaultWidget);
                } else {
                    defaultWidget = $('meta[name=widget_type]');
                    if (defaultWidget.length > 0) {
                        loadFormWidget(defaultWidget.attr('content'));
                    }
                }

                $('select[name=type]').change(function(e) {
                    e.preventDefault();
                    loadFormWidget($(this).val());
                });
            });
        })(jQuery);
    </script>
@endpush
