@php
    $royalty = $post
        ->royalties()
        ->whereIn('status_id', [1, 2, 3])
        ->get()
        ->first();
@endphp

<div class="row">
    @if ($post->published === -1)
        <div class="col-md-12">
            <p class="alert alert-danger">
                <strong>Bài viết này đã bị hủy, lý do: </strong> {{ $post->cancel_message ?: 'Không rõ' }}
            </p>
        </div>
    @endif
    <div class="col-lg-8">
        @component('components.block')
            @slot('title', trans('language.basic_info'))
            <div class="block-body">
                <div class="form-bordered">
                    <ul class="nav nav-tabs" data-toggle="tabs">
                        @foreach (config('cnv.languages') as $language)
                            <li {{ $loop->first ? 'class=active' : '' }}>
                                <a href="#{{ $language['locale'] }}">
                                    {{ $language['name'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content">
                        @foreach (config('cnv.languages') as $language)
                            <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="{{ $language['locale'] }}">
                                <div class="form-group">
                                    {!! Form::label('name', trans('language.name_post'), ['class' => 'label-control']) !!}
                                    {!! Form::text('language[' . $language['locale'] . '][name]', @$post->language('name', $language['locale']), [
                                        'class' => 'form-control',
                                        'required',
                                    ]) !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::label('description', trans('language.description_post'), ['class' => 'label-control']) !!}
                                    {!! Form::textarea(
                                        'language[' . $language['locale'] . '][description]',
                                        @$post->language('description', $language['locale']),
                                        ['class' => 'form-control', 'rows' => 3],
                                    ) !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::label('description', trans('language.quote_post'), ['class' => 'label-control']) !!}
                                    {!! Form::textarea(
                                        'language[' . $language['locale'] . '][quote]',
                                        @$post->language('quote', $language['locale']),
                                        ['class' => 'form-control simple_editor'],
                                    ) !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::label('description', trans('language.content'), ['class' => 'label-control']) !!}
                                    {!! Form::textarea(
                                        'language[' . $language['locale'] . '][content]',
                                        @$post->language('content', $language['locale']),
                                        ['class' => 'form-control editor', 'required'],
                                    ) !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::label('description', trans('language.note_post'), ['class' => 'label-control']) !!}
                                    {!! Form::textarea(
                                        'language[' . $language['locale'] . '][note]',
                                        @$post->language('note', $language['locale']),
                                        ['class' => 'form-control simple_editor'],
                                    ) !!}
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        @endcomponent
        @include('seo_plugin::form', ['base' => 'tin-tuc', 'model' => $post])
        @include('custom_field::custom_fields', ['module' => 'blog', 'model' => $post])
    </div>
    <div class="col-lg-4">
        @component('components.block')
            @slot('title', trans('language.setting_field'))
            <div class="block-body">
                <div class="form-horizontal form-bordered">
                    <div class="form-group">
                        {!! Form::label('user_id', 'Giới thiệu', ['class' => 'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::select('prefix', ['' => 'Không có', 'HQ Online' => 'HQ Online', 'HQVN' => 'HQVN'], $post->prefix, [
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                    </div>

                    @if (allow('news.post.approved_level_3') || allow('news.post.approved_level_2'))
                        <div class="form-group">
                            {!! Form::label('user_id', trans('news::language.author'), ['class' => 'control-label col-md-4']) !!}
                            <div class="col-md-8">
                                {!! Form::select('user_id', get_list_authors_for_choose(), $post->id ? $post->user_id : auth()->user()->id, [
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div>
                    @else
                        {!! Form::hidden('user_id', $post->id ? $post->user_id : auth()->user()->id) !!}
                    @endif

                    <div class="form-group">
                        {!! Form::label('featured', trans('news::language.featured'), ['class' => 'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            <label class="switch switch-warning">
                                <input type="checkbox" name="featured" value="1"
                                    {{ @$post->featured ? 'checked' : '' }}>
                                <span></span>
                            </label>
                        </div>
                    </div>

                    <div class="show_featured_datetime {{ @$post->featured ? '' : 'hide' }}">
                        <div class="form-group">
                            <div class="col-md-12 help-block">
                                <span>Ngày hiển thị tin tiêu điểm</span>
                            </div>
                            <div class="col-md-7">
                                {!! Form::text(
                                    'date_featured_started_at',
                                    @$post->getOriginal('featured_started_at') ? @$post->featured_started_at->format('d-m-Y') : '',
                                    ['class' => 'form-control input-datepicker'],
                                ) !!}
                            </div>
                            <div class="col-md-5">
                                <div class="input-group bootstrap-timepicker timepicker">
                                    {!! Form::text(
                                        'time_featured_started_at',
                                        @$post->getOriginal('featured_started_at') ? @$post->featured_started_at->format('H:i') : '',
                                        ['class' => 'form-control input-timepicker24'],
                                    ) !!}
                                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12 help-block">
                                <span>Ngày ẩn tin tiêu điểm</span>
                            </div>
                            <div class="col-md-7">
                                {!! Form::text(
                                    'date_featured_ended_at',
                                    @$post->getOriginal('featured_ended_at') ? @$post->featured_ended_at->format('d-m-Y') : '',
                                    ['class' => 'form-control input-datepicker'],
                                ) !!}
                            </div>
                            <div class="col-md-5">
                                <div class="input-group bootstrap-timepicker timepicker">
                                    {!! Form::text(
                                        'time_featured_ended_at',
                                        @$post->getOriginal('featured_ended_at') ? @$post->featured_ended_at->format('H:i') : '',
                                        ['class' => 'form-control input-timepicker24'],
                                    ) !!}
                                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        {!! Form::label('status', 'Ẩn bản tin', ['class' => 'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            <label class="switch switch-success">
                                <input type="checkbox" name="status" value="1" {{ @$post->status ? 'checked' : '' }}>
                                <span></span>
                            </label>
                        </div>
                    </div>

                    @if (allow('news.post.approved_level_1') && $post->published >= 3)
                        <div class="form-group show_publish_datetime">
                            <div class="col-md-12 help-block">
                                <span>Ngày bắt đầu hiển thị</span>
                            </div>
                            <div class="col-md-7">
                                {!! Form::text(
                                    'date_published',
                                    @$post->getOriginal('published_at') ? @$post->published_at->format('d-m-Y') : '',
                                    ['class' => 'form-control input-datepicker'],
                                ) !!}
                            </div>
                            <div class="col-md-5">
                                <div class="input-group bootstrap-timepicker timepicker">
                                    {!! Form::text(
                                        'time_published',
                                        @$post->getOriginal('published_at') ? @$post->published_at->format('H:i') : '',
                                        ['class' => 'form-control input-timepicker24'],
                                    ) !!}
                                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                </div>
                            </div>
                            <div class="col-md-12 help-block">
                                <p>Để trống các ô trên để ẩn bài viết</p>
                            </div>
                        </div>
                    @endif

                    @if ($post->could_be_approved_post)
                        <div class="form-group">
                            {!! Form::label('published', trans('news::language.publish'), ['class' => 'control-label col-md-4']) !!}
                            <div class="col-md-8">
                                <label class="switch switch-primary">
                                    <input type="checkbox" name="published" value="1"
                                        {{ @$post->approved ? 'checked' : '' }}>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    @endif

                    <div class="form-group">
                        {!! Form::label('published', trans('language.status'), ['class' => 'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            @if ($post->id)
                                @php
                                    switch ($post->published):
                                        case -1:
                                            printf(
                                                '<p class="form-control-static %s">%s</p>',
                                                'text-danger',
                                                'Đã bị hủy',
                                            );
                                            break;
                                        case 1:
                                            printf(
                                                '<p class="form-control-static %s">%s</p>',
                                                'text-warning',
                                                trans('news::language.waiting_level_2'),
                                            );
                                            break;
                                        case 2:
                                            printf(
                                                '<p class="form-control-static %s">%s</p>',
                                                'text-warning',
                                                trans('news::language.waiting_level_3'),
                                            );
                                            break;
                                        case 3:
                                            printf(
                                                '<p class="form-control-static %s">%s</p>',
                                                'text-success',
                                                trans('news::language.approved_by_level_3'),
                                            );
                                            break;
                                        default:
                                            printf(
                                                '<p class="form-control-static %s">%s</p>',
                                                'text-warning',
                                                trans('news::language.waiting_level_1'),
                                            );
                                            break;
                                    endswitch;
                                @endphp
                            @else
                                <p class="form-control-static text-warning">-</p>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        @endcomponent

        @component('components.block')
            @slot('title', trans('news::language.choose_category'))
            @slot('action', link_to_route('admin.post.category.index', trans('news::language.category_create'), null,
                ['class' => 'btn btn-xs btn-primary', 'target' => '_blank', 'required']))
                <div class="block-body">
                    <div class="form_group">
                        {!! Form::select(
                            'category[]',
                            app(\Modules\News\Models\PostCategory::class)->getParentForSelection(null, false, false),
                            @$post->categories->map->id->toArray(),
                            ['class' => 'form-control', 'multiple' => true],
                        ) !!}
                    </div>
                </div>
            @endcomponent
            @component('components.block')
                @slot('title', trans('news::language.horizon_thumbnail'))
                <div class="block-body">
                    <div class="form_group">
                        <div class="choose-thumbnail">
                            {!! Form::hidden('thumbnail', $post->thumbnail, ['id' => 'thumbnail']) !!}
                        </div>
                    </div>
                </div>
            @endcomponent
            @component('components.block')
                @slot('title', trans('news::language.vertical_thumbnail') . ' (' . trans('news::language.optional') . ')')
                <div class="block-body">
                    <div class="form_group">
                        <div class="choose-thumbnail vertical">
                            {!! Form::hidden('thumbnail_vertical', $post->thumbnail_vertical, ['id' => 'thumbnail_vertical']) !!}
                        </div>

                        <p class="describe">Tỉ lệ (9 / 16)</p>
                    </div>
                </div>
            @endcomponent
            @component('components.block')
                @slot('title', trans('news::language.extra_info'))
                <div class="block-body">
                    <div class="form-group">
                        {!! Form::label('name', trans('language.name2_post'), ['class' => 'label-control']) !!}
                        {!! Form::text(
                            'language[' . $language['locale'] . '][second_name]',
                            @$post->language('second_name', $language['locale']),
                            ['class' => 'form-control'],
                        ) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('name', 'Tiêu đề thứ 3', ['class' => 'label-control']) !!}
                        {!! Form::text(
                            'language[' . $language['locale'] . '][third_name]',
                            @$post->language('third_name', $language['locale']),
                            ['class' => 'form-control'],
                        ) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('name', trans('language.tags_post'), ['class' => 'label-control']) !!}
                        {!! Form::text('language[' . $language['locale'] . '][tags]', @$post->language('tags', $language['locale']), [
                            'class' => 'form-control input-tags',
                        ]) !!}
                    </div>
                </div>
            @endcomponent

            @if (allow('royalty.royalty.index') && allow('royalty.royalty.create'))
                @component('components.block')
                    @slot('title', trans('royalty::language.choose_category'))
                    <div class="block-body">
                        @php
                            $readonly = false;
                            if (isset($royalty) && isset($royalty->status)) {
                                $classes = ['', 'warning', 'info', 'success', 'danger'];
                                echo '<div class="alert alert-' .
                                    $classes[$royalty->status_id] .
                                    '">' .
                                    $royalty->status->name .
                                    '</div>';

                                if ($royalty->status_id == 2 || $royalty->status_id == 3) {
                                    $readonly = true;
                                }
                            }
                        @endphp
                        <div class="form-horizontal form-bordered">
                            <div class="form-group">
                                {!! Form::label('royalty', 'Có nhuận bút', ['class' => 'control-label col-md-4']) !!}
                                <div class="col-md-8">
                                    <label class="switch switch-success {{ @$readonly ? 'disabled' : '' }}">
                                        <input type="checkbox" {{ @$readonly ? 'disabled readonly' : '' }} name="add-royalty"
                                            value="1" {{ @$royalty ? 'checked' : '' }}>
                                        <span></span>
                                    </label>
                                </div>
                                <input type="hidden" name="royalty[id]" value="{{ @$royalty ? $royalty->id : 0 }}">
                                <input type="hidden" name="royalty[month]"
                                    value="{{ @$royalty ? $royalty->month : Carbon\Carbon::now()->format('Y-m') }}">
                                <input type="hidden" name="royalty[status_id]"
                                    value="{{ @$royalty ? $royalty->status_id : 1 }}">
                                <input type="hidden" name="royalty[amount]" value="{{ @$royalty ? $royalty->amount : 0 }}">
                            </div>
                            <div id="royalty-config-wrapper" class="{{ @$royalty ? '' : 'hide' }}">
                                <div class="form-group">
                                    {!! Form::label('royalty[user_id]', trans('royalty::language.claim_for'), [
                                        'class' => 'control-label col-md-4',
                                    ]) !!}
                                    <div class="col-md-8">
                                        {!! @$readonly
                                            ? '<div style="padding: 7px 0"><b>' . $royalty->author->name . '</b></div>'
                                            : Form::select(
                                                'royalty[user_id]',
                                                get_list_authors_for_choose(),
                                                isset($royalty) && $royalty->user_id ? $royalty->user_id : auth()->user()->id,
                                                [
                                                    'class' => 'form-control',
                                                    'style' => 'width: 100%!important',
                                                ],
                                            ) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('royalty[category_id]', trans('royalty::language.type_category'), [
                                        'class' => 'control-label col-md-4',
                                    ]) !!}
                                    <div class="col-md-8">
                                        {!! @$readonly
                                            ? '<div style="padding: 7px 0">' . $royalty->category->name . '</div>'
                                            : Form::select(
                                                'royalty[category_id]',
                                                get_list_royalty_category_to_choose(),
                                                isset($royalty) && $royalty->cateogry_id ? $royalty->cateogry_id : 0,
                                                ['class' => 'form-control', 'style' => 'width: 100%!important'],
                                            ) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcomponent
            @endif
        </div>
    </div>

    @include('partial.editor')

    @push('footer')
        <script>
            "use strict";
            (function($) {
                $('input[name="featured"]').on('change', function() {
                    if ($(this).is(":checked")) $('.show_featured_datetime').removeClass('hide');
                    else $('.show_featured_datetime').addClass('hide');
                });

                $('input[name="status"]').on('change', function() {
                    if (!$(this).is(":checked")) $('.show_publish_datetime').removeClass('hide');
                    else $('.show_publish_datetime').addClass('hide');
                });

                $('input[name="add-royalty"]').on('change', function() {
                    if ($(this).is(":checked")) $('#royalty-config-wrapper').removeClass('hide');
                    else $('#royalty-config-wrapper').addClass('hide');
                });
            })(jQuery);
        </script>
    @endpush
