@php
    $getAllCustomFields = \Modules\CustomField\Models\Field::module($module)->activated()->get();
@endphp
@if($getAllCustomFields->count())
    @component('components.block')
        @slot('title', trans('custom_field::language.custom_field'))
        <ul class="nav nav-tabs" data-toggle="tabs">
            @foreach(config('cnv.languages') as $language)
                <li {{ $loop->first ? 'class=active' : '' }}>
                    <a href="#{{ $language['locale'] }}_custom_fields">
                        {{ $language['name'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="tab-content" style="padding-top: 20px">

            @foreach(config('cnv.languages') as $language)
                @php $loopParrent = $loop; @endphp
                <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="{{ $language['locale'] }}_custom_fields">
                    @foreach($getAllCustomFields as $field)
                        @if($loopParrent->first)
                            <input type="hidden" name="customfield[{{ $field->slug }}][field_id]" value="{{ $field->id }}">
                            <input type="hidden" name="customfield[{{ $field->slug }}][module_id]" value="{{ $model->id }}">
                            <input type="hidden" name="customfield[{{ $field->slug }}][module_type]" value="{{ get_class($model) }}">
                        @endif

                        {!! app('custom.field')->getField($field)->form($field, $model, $language['locale']) !!}
                        <br>
                    @endforeach
                </div>
            @endforeach
        </div>
    @endcomponent
@endif