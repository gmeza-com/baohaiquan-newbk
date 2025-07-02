<div class="form_group">
    <label for="{{ $field->slug }}" class="control-label">{{ $field->language('name') }}</label>
    <select name="customfield[{{ $field->slug }}][language][{{ $locale }}]" class="form-control">
        @foreach($field->typeDatas as $type)
            <option value="{{ $type->language('value', $locale) }}" {{ $type->language('value', $locale) == $model->customField($locale)->{$field->slug} ? 'selected' : '' }}>{{ $type->language('value', $locale) }}</option>
        @endforeach
    </select>
</div>