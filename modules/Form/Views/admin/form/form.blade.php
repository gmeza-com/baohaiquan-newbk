@component('components.block')
    @slot('title', 'Forms')
    <div class="block-body">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="form-group">
                    <label for="slug" class="col-md-3 control-label">
                        Type
                    </label>
                    <div class="col-md-9">
                        {!! Form::text('slug', $form->slug, ['class' => 'form-control', 'required']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endcomponent

@component('components.block')
    @slot('title', 'Fields')
    @slot('action')
        <button type="button" class="btn btn-xs btn-success" id="add-field">
            <i class="fa fa-plus"></i>
        </button>
    @endslot
    <div class="block-body">
        @php $max = !$form->field ? 0 : count($form->field); @endphp
        <div class="row" id="entry-field" data-max="{{ $max }}">
            @if ($max)
                @foreach ($form->field as $key => $field)
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-md-1">
                                <button type="button" class="btn btn-xs btn-danger" id="remove-field">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            </div>
                            <div class="col-md-3">
                                {!! Form::text('field[' . $key . '][slug]', @$field['slug'], [
                                    'class' => 'form-control',
                                    'placeholder' => 'Slug',
                                ]) !!}
                            </div>

                            <div class="col-md-3">
                                {!! Form::text('field[' . $key . '][type]', @$field['type'], [
                                    'class' => 'form-control',
                                    'placeholder' => 'Type',
                                ]) !!}
                            </div>

                            <div class="col-md-5">
                                {!! Form::textarea('field[' . $key . '][option]', @$field['option'], [
                                    'class' => 'form-control',
                                    'placeholder' => 'Option',
                                    'rows' => 5,
                                ]) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endcomponent

@push('footer')
    <script id="item-field" type="text/template">
    <div class="col-md-12">
        <div class="form-group">
            <div class="col-md-1">
                <button type="button" class="btn btn-xs btn-danger" id="remove-field">
                    <i class="fa fa-trash-o"></i>
                </button>
            </div>
            <div class="col-md-3">
                {!! Form::text('field[__KEY__][slug]', null, ['class' => 'form-control', 'placeholder' => 'Slug']) !!}
            </div>

            <div class="col-md-3">
                {!! Form::text('field[__KEY__][type]', null, ['class' => 'form-control', 'placeholder' => 'Type']) !!}
            </div>

            <div class="col-md-5">
                {!! Form::textarea('field[__KEY__][option]', null, ['class' => 'form-control', 'placeholder' => 'Option', 'rows' => 5]) !!}
            </div>
        </div>
    </div>
</script>
    <script>
        $(document).on('click', '#remove-field', function(e) {
            var entry = $('#entry-field');
            var max = entry.data('max') - 1;
            entry.data('max', max);

            $(this).parent().parent().remove();
        });

        $(document).on('click', '#add-field', function(e) {
            var template = $('#item-field').html();
            var entry = $('#entry-field');
            var max = entry.data('max') + 1;

            template = template.replace(/__KEY__/g, max);
            entry.prepend(template);
            entry.data('max', max);
        });
    </script>
@endpush
