<div class="form_group">
    <label for="{{ $field->slug }}" class="control-label">{{ $field->language('name') }}</label>
    <input type="text" name="customfield[{{ $field->slug }}][language][{{ $locale }}]" class="form-control" value="{{ $model->customField($locale)->{$field->slug} }}">
</div>