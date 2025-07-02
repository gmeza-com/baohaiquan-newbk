@php
    $modules = collect($module_in_menu_search_hook);
    $moduleName = $modules->mapWithKeys(function($value) {
        return [$value['url'] => $value['name']];
    });
@endphp

<div class="modal fade" id="search-links" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{ trans('menu::language.search_links') }}</h4>
            </div>
            <div class="modal-body">
            {!! Form::open(['url' => '#', 'class' => 'form-horizontal form-bordered form-validate', 'id' => 'form-search-links']) !!}
                <div class="form-group">
                    {!! Form::label('module_name', trans('menu::language.search_module'), ['class' => 'label-control col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::select('module_name', $moduleName, null, ['class' => 'form-control select-select2', 'style' => 'width: 100% !important;']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('menu_keyword', trans('menu::language.search_link'), ['class' => 'label-control col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::select('menu_keyword', [], null, ['class' => 'form-control select-search-link', 'style' => 'width: 100% !important;']) !!}
                    </div>
                </div>

            {!! Form::close() !!}

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('language.close') }}</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
