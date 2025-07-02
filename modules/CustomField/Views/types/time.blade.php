<div class="form_group">
    <label for="{{ $field->slug }}" class="control-label">{{ $field->language('name') }}</label>
    <div class="input-group bootstrap-timepicker timepicker">
        <input type="text" name="customfield[{{ $field->slug }}][language][{{ $locale }}]" class="form-control input-timepicker24" value="{{ $model->customField($locale)->{$field->slug} }}">
        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
    </div>
</div>