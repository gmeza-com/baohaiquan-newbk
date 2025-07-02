<div class="form_group">
    <label for="{{ $field->slug }}" class="control-label">{{ $field->language('name') }}</label>
    <textarea name="customfield[{{ $field->slug }}][language][{{ $locale }}]" class="editor">{{ $model->customField($locale)->{$field->slug} }}</textarea>
</div>