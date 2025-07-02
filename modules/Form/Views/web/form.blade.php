
<form method="POST" action="/form/{{ $form->slug }}" class="form-horizontal form-validate" data-callback="reload_page">
    @foreach($form->field as $field)
        <div class="form-group">
            <label for="{{ $field['slug'] }}" class="control-label col-md-3">{{ trans('custom.form.' . $field['slug']) }}</label>
            <div class="col-md-9">
                @if(in_array($field['type'], ['text', 'number', 'email']))
                    <input type="{{ $field['type'] }}" id="{{ $field['slug'] }}" name="{{ $field['slug'] }}" class="form-control" >
                @elseif($field['type'] == 'textarea')
                    <textarea name="{{ $field['slug'] }}" id="{{ $field['slug'] }}" cols="30" class="form-control"></textarea>
                @elseif($field['type'] == 'radio')
                    @foreach(explode(PHP_EOL, $field['option']) as $option)
                        <input type="radio" name="{{ $field['slug'] }}" value="{{ $option }}" id="{{ $option }}">
                        <label for="{{ $option }}">{{ $option }}</label>
                    @endforeach
                @elseif($field['type'] == 'checkbox')
                    <input type="checkbox" name="{{ $field['slug'] }}" value="{{ $field['option'] }}" id="{{ $field['option'] }}">
                    <label for="{{ $field['option'] }}">{{ $field['option'] }}</label>
                @elseif($field['type'] == 'select')
                    <select name="{{ $field['slug'] }}" class="form-control">
                        @foreach(explode(PHP_EOL, $field['option']) as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
        </div>
    @endforeach

    <div class="form-group">
        <div class="col-md-9 col-md-offset-3">
            <button type="submit" class="btn btn-primary btn-alt"><i class="fa fa-paper-plane"></i> OK</button>
        </div>
    </div>
</form>