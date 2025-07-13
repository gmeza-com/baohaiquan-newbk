@push('header')
    <link rel="stylesheet" href="/backend/css/longform.css">
    <link rel="stylesheet" href="/backend/css/editorjs-render.css">
@endpush

<meta name="gallery-type" content="{{ @$gallery->type }}">
<div class="row">
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
                                    {!! Form::label('name', trans('language.name'), ['class' => 'label-control']) !!}
                                    {!! Form::text('language[' . $language['locale'] . '][name]', @$gallery->language('name', $language['locale']), [
                                        'class' => 'form-control',
                                        'required',
                                    ]) !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::label('description', trans('language.description'), ['class' => 'label-control']) !!}
                                    {!! Form::textarea(
                                        'language[' . $language['locale'] . '][description]',
                                        @$gallery->language('description', $language['locale']),
                                        ['class' => 'form-control', 'required'],
                                    ) !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endcomponent


        @include('seo_plugin::form', [
            'base' => @$gallery->categories->first()
                ? @$gallery->categories->first()->language('slug', config('cnv.languages')[0]['locale'])
                : ':slug',
            'model' => $gallery,
        ])
        <div id="form-gallery"></div>

    </div>
    <div class="col-lg-4">
        @component('components.block')
            @slot('title', trans('language.setting_field'))
            <div class="block-body">
                <div class="form-horizontal form-bordered">
                    @if (!$gallery->type)
                        <div class="form-group">
                            {!! Form::label('type', trans('gallery::language.type'), ['class' => 'control-label col-md-4']) !!}
                            <div class="col-md-8">
                                {!! Form::select(
                                    'type',
                                    ['album' => 'Album', 'video' => 'Video', 'audio' => 'Audio', 'longform' => 'Longform'],
                                    null,
                                    [
                                        'class' => 'form-control',
                                    ],
                                ) !!}
                            </div>
                        </div>
                    @endif

                    <div class="form-group">
                        {!! Form::label('featured', trans('gallery::language.featured'), ['class' => 'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            <label class="switch switch-warning">
                                <input type="checkbox" name="featured" value="1"
                                    {{ @$gallery->featured ? 'checked' : '' }}>
                                <span></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('published', trans('language.published'), ['class' => 'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            <label class="switch switch-primary">
                                <input type="checkbox" name="published" value="1"
                                    {{ @$gallery->published ? 'checked' : '' }}>
                                <span></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <a href="javascript:void(0);"
                            onclick="toggleThisElement('#show_publish_datetime');return false;">{{ trans('language.set_a_specific_publish_date') }}</a>
                    </div>
                    <div class="form-group" id="show_publish_datetime" style="display: none">
                        <div class="col-md-7">
                            {!! Form::text('date_published', \Carbon\Carbon::parse(@$gallery->published_at)->format('d-m-Y'), [
                                'class' => 'form-control input-datepicker',
                            ]) !!}
                        </div>
                        <div class="col-md-5">
                            <div class="input-group bootstrap-timepicker timepicker">
                                {!! Form::text('time_published', @\Carbon\Carbon::parse(@$gallery->published_at)->format('H:i'), [
                                    'class' => 'form-control input-timepicker24',
                                ]) !!}
                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcomponent

        @component('components.block')
            @slot('title', trans('gallery::language.choose_category'))
            @slot('action', link_to_route('admin.gallery.category.index', trans('gallery::language.category_create'), null,
                ['class' => 'btn btn-xs btn-primary', 'target' => '_blank', 'required']))
                <div class="block-body">
                    <div class="form_group">
                        {!! Form::select(
                            'category[]',
                            (new \Modules\Gallery\Models\GalleryCategory())->getForSelection(),
                            @$gallery->categories->map->id->toArray(),
                            ['class' => 'form-control', 'multiple' => true],
                        ) !!}
                    </div>
                </div>
            @endcomponent

            @component('components.block')
                @slot('title', trans('language.thumbnail'))
                <div class="block-body">
                    <div class="form_group">
                        <div class="choose-thumbnail">
                            {!! Form::hidden('thumbnail', $gallery->thumbnail, ['id' => 'thumbnail']) !!}
                        </div>
                    </div>
                </div>
            @endcomponent
            </>
        </div>

        @include('partial.editor')
        @include('partial.editor-js')

        @php
            $content = @$gallery->language('content', $language['locale']);
        @endphp

        @push('footer')
            <div class="modal fade longform-preview" id="data" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body"></div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->


            <script>
                "use strict";

                (function($) {
                    var showData = function(id, locale) {
                        $('.modal-body').html('');

                        $.get(`{{ route('api.gallery.show', ['id' => ':id']) }}?locale=${locale}`.replace(
                                ':id', id),
                            function(data) {
                                $('.modal-body').html(data);
                                $('#data').modal('show');
                            });
                    }

                    window.showData = showData;


                    var loadFormWidget = function($type) {
                        $.get('{{ request()->fullUrl() }}?type=' + $type, function($data) {
                            $('#form-gallery').html($data);
                            editor().init();
                            Main().init();

                            if ($type === 'longform') {

                                const data = JSON.parse(@json($content)?.[0]);

                                // first define the tools to be made avaliable in the columns
                                let column_tools = {
                                    header: {
                                        class: Header,
                                    },
                                    paragraph: {
                                        class: Paragraph,
                                    },
                                    image: {
                                        class: ImageTool,
                                        config: {
                                            moxman: moxman,
                                            captionPlaceholder: "Nhập mô tả hình ảnh",
                                            buttonContent: "Chọn hình ảnh",
                                            features: {
                                                background: false,
                                                border: false,
                                                stretch: false,
                                            }
                                        }
                                    }
                                }

                                const editorjs = new EditorJS({
                                    holder: 'longform-content-' + '{{ $language['locale'] }}',
                                    tools: {
                                        embed: Embed,
                                        quote: {
                                            class: Quote,
                                            config: {
                                                defaultType: "quotationMark",
                                            },
                                        },

                                        header: {
                                            class: Header,
                                        },
                                        paragraph: {
                                            class: Paragraph,
                                            inlineToolbar: true,
                                        },
                                        columns: {
                                            class: editorjsColumns,
                                            config: {
                                                EditorJsLibrary: EditorJS, // Pass the library instance to the columns instance.
                                                tools: column_tools // IMPORTANT! ref the column_tools
                                            }
                                        },
                                        delimiter: Delimiter,
                                        image: {
                                            class: ImageTool,
                                            config: {
                                                moxman: moxman,
                                                captionPlaceholder: "Nhập mô tả hình ảnh",
                                                buttonContent: "Chọn hình ảnh",
                                                features: {
                                                    background: false,
                                                    border: false,
                                                }
                                            }
                                        },
                                    },
                                    data,
                                    i18n: {
                                        messages: {
                                            ui: {
                                                blockTunes: {
                                                    toggler: {
                                                        "Click to tune": "Bấm để điều chỉnh",
                                                        "or drag to move": "hoặc nắm kéo để di chuyển",
                                                    },

                                                },
                                                inlineToolbar: {
                                                    converter: {
                                                        "Convert to": "Chuyển thành",
                                                    },
                                                },
                                                toolbar: {
                                                    toolbox: {
                                                        Add: "Thêm",
                                                    },
                                                },
                                            },
                                            tools: {
                                                image: {
                                                    "Stretch image": "Mở rộng",
                                                }
                                            },
                                            toolNames: {
                                                Text: "Đoạn văn",
                                                Heading: "Tiêu đề đoạn",
                                                List: "Danh sách",
                                                // Checklist: "Danh mục kiểm tra",
                                                Quote: "Trích dẫn",
                                                Delimiter: "Dấu phân cách",
                                                Link: "Liên kết",
                                                Bold: "Đậm",
                                                Italic: "Nghiêng",
                                                SimpleImage: "Hình ảnh",
                                                Image: "Hình ảnh",
                                            },
                                            blockTunes: {
                                                delete: {
                                                    Delete: "Xóa bỏ",
                                                },
                                                moveUp: {
                                                    "Move up": "Di chuyển lên",
                                                },
                                                moveDown: {
                                                    "Move down": "Di chuyển xuống",
                                                },
                                            },
                                        },
                                    }
                                });

                                // Trigger EditorJS ready event
                                $(document).trigger('editorjs:ready');

                                // Ensure EditorJS content is ignored by form validation
                                $('.longform-content, .ignore-validation').find('input, textarea, select').each(
                                    function() {
                                        $(this).rules('add', {
                                            ignoreEditorJS: true
                                        });
                                    });


                                // Store editorjs instance globally for form submission handling
                                window.editorjsInstance = editorjs;
                            }
                        });
                    };


                    $(document).ready(function() {
                        var defaultWidget = $('select[name=type]').val();

                        if ($('select[name=type]').length > 0) {
                            loadFormWidget(defaultWidget);
                            var currentWidget = defaultWidget;

                        } else {
                            defaultWidget = $('meta[name="gallery-type"]');


                            if (defaultWidget.length > 0) {
                                loadFormWidget(defaultWidget.attr('content'));
                                currentWidget = defaultWidget.attr('content');
                            }
                        }

                        $('select[name=type]').change(function(e) {
                            e.preventDefault();
                            const _nextWidget = $(this).val();
                            if (_nextWidget !== currentWidget) {
                                loadFormWidget(_nextWidget);
                                currentWidget = _nextWidget;
                            }
                        });

                        $('[name="category[]"]').on('change', function() {
                            var selectedCategories = $(this).val();

                            if (!selectedCategories || (selectedCategories?.length ?? 0) < 1) {

                                $('[data="base_{{ $language['locale'] }}"]').text(':slug/');


                                return;
                            }

                            $.ajax({
                                url: '{{ route('api.gallery.category.get', ['id' => ':id']) }}'
                                    .replace(':id',
                                        selectedCategories[0]),
                                method: 'GET',
                                success: function(response) {
                                    const catSlug = response?.result?.languages?.find(lang =>
                                        lang
                                        .locale ===
                                        '{{ $language['locale'] }}')?.slug;

                                    $('[data="base_{{ $language['locale'] }}"]').text(catSlug +
                                        '/');

                                }
                            });
                        });


                        // Handle EditorJS validation ignoring for dynamically created content
                        $(document).on('DOMNodeInserted', '.longform-content, .ignore-validation', function() {
                            var $container = $(this);
                            // Add a small delay to ensure EditorJS is fully initialized
                            setTimeout(function() {
                                $container.find(
                                    'input, textarea, select, .codex-editor__redactor, .codex-editor__redactor *'
                                ).each(function() {
                                    $(this).rules('add', {
                                        ignoreEditorJS: true
                                    });
                                });
                            }, 100);
                        });

                        var handleSubmit = function() {

                            if (currentWidget === 'longform') {

                                window.editorjsInstance.save().then((data) => {

                                    const dataJson = JSON.stringify(data);

                                    $('#editor-content-{{ $language['locale'] }}').val(dataJson);

                                    // Use this.submit() instead of $(this).submit() to avoid infinite loop
                                    $('#save').submit();
                                }).catch((error) => {
                                    console.error('Error saving EditorJS data:', error);
                                    alert('Please check the content before saving.');
                                });


                            } else {
                                $('#save').submit();
                            }
                        }

                        window.handleSubmit = handleSubmit;
                    });


                })(jQuery);
            </script>
        @endpush
