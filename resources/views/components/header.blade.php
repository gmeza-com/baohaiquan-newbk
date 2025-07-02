<div class="pull-right">
    <a href="{{ $url }}" class="btn btn-default">
        <i class="fa fa-arrow-circle-left"></i> {{ trans('language.back') }}
    </a>
    <button type="button" class="btn btn-primary" onclick="submitForm('#save');">
        <i class="fa fa-save"></i> {{ trans('language.save') }}
    </button>
    <button type="button" class="btn btn-success" onclick="submitFormAndCreate('#save');">
        <i class="fa fa-save"></i> {{ trans('language.save_and_create') }}
    </button>
</div>

<h1>
    {{ $title }}
</h1>