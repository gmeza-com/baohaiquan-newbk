@component('components.block')
    @slot('title', 'Longform')
    <div class="block-body">
        <div class="form-bordered">
            <ul class="nav nav-tabs" data-toggle="tabs">
                @foreach (config('cnv.languages') as $language)
                    <li {{ $loop->first ? 'class=active' : '' }}>
                        <a href="#{{ $language['locale'] }}_longform">
                            {{ $language['name'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content">
                @foreach (config('cnv.languages') as $language)
                    @php $content = $gallery->language('content', $language['locale']) ?: collect([]); @endphp
                    <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="{{ $language['locale'] }}_longform">
                        <div class="longform-container ignore-validation">
                            <div class="longform-nav">
                                <button onclick="expandEditor('{{ $language['locale'] }}')" type="button"
                                    class="btn btn-primary"><i class="fa fa-arrows-alt" aria-hidden="true"></i> Mở
                                    rộng</button>
                            </div>

                            <textarea id="editor-content-{{ $language['locale'] }}" name="language[{{ $language['locale'] }}][content]" hidden></textarea>
                            <div id="longform-content-{{ $language['locale'] }}" class="longform-content"></div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="modal fade longform-preview" id="data" role="dialog">
        <div class="modal-dialog modal-fullscreen" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="header-container">
                        <div class="btn-group" role="group" aria-label="...">
                            <button id="edit-modal" type="button" class="btn btn-default">Chỉnh sửa</button>
                            <button id="preview-modal" type="button" class="btn btn-default">Xem trước</button>
                        </div>

                        <button id="close-modal" type="button" class="btn btn-primary">
                            <i class="fa fa-compress" aria-hidden="true"></i> Thu gọn</button>
                    </div>


                </div>
                <div class="modal-body"></div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endcomponent

<script>
    "use strict";

    (function($) {
        var currentLocale = '{{ config('cnv.languages')[0]['locale'] }}';
        var currentModalData = {};

        $('#edit-modal').on('click', function() {
            // TODO: kiểm tra nếu đang active thì không làm gì
            if (this.classList.contains('btn-primary')) {
                return;
            }


            this.classList.add('btn-primary');
            this.classList.remove('btn-default');

            $('#preview-modal').removeClass('btn-primary');
            $('#preview-modal').addClass('btn-default');


            $('#data .modal-body').html('');

            $.ajax({
                url: `{{ route('api.longform.show') }}`,
                method: 'POST',
                data: {
                    editorjs_data: JSON.stringify(currentModalData),
                },
                success: function(data) {
                    $('#data').modal('show');

                    setTimeout(() => {
                        $('#data .modal-body').html(data);
                    }, 200);

                }
            });

        });

        $('#preview-modal').on('click', async function() {

            // TODO: kiểm tra nếu đang active thì không làm gì
            if (this.classList.contains('btn-primary')) {
                return;
            }

            currentModalData = await window['editorjsInModal'].save();


            this.classList.add('btn-primary');
            this.classList.remove('btn-default');

            $('#edit-modal').removeClass('btn-primary');
            $('#edit-modal').addClass('btn-default');

            $(`#data .modal-body`).html('');

            $.ajax({
                url: `{{ route('api.longform.preview') }}`,
                method: 'POST',
                data: {
                    editorjs_data: JSON.stringify(currentModalData),
                },
                success: function(data) {
                    $('#data .modal-body').html(data);
                    $('#data').modal('show');
                }
            });

        });

        $('#close-modal').on('click', async function() {

            // TODO: lấy dữ liệu trong modal ra và lưu vào trong editorjsInstance
            const editorjsModalData = await window['editorjsInModal'].save();

            await window['editorjsInstance_' + currentLocale].render(editorjsModalData);

            $('#data').modal('hide');

            // reset 2 cái button
            $('#edit-modal').removeClass('btn-primary').addClass('btn-default');
            $('#preview-modal').removeClass('btn-primary').addClass('btn-default');

        });

        var initializeLongformEditor = function() {
            @foreach (config('cnv.languages') as $language)
                @php
                    $content = @$gallery->language('content', $language['locale']);
                @endphp

                const data_{{ $language['locale'] }} = (() => {
                    try {
                        const parsed = JSON.parse(@json($content)?.[0]);
                        return parsed || {};
                    } catch (e) {
                        return {};
                    }
                })();

                // Define tools for columns
                let column_tools_{{ $language['locale'] }} = {
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
                };

                const editorjs_{{ $language['locale'] }} = new EditorJS({
                    holder: 'longform-content-{{ $language['locale'] }}',
                    placeholder: 'Nhập nội dung...',
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
                                EditorJsLibrary: EditorJS,
                                tools: column_tools_{{ $language['locale'] }}
                            }
                        },
                        delimiter: Delimiter,
                        image: {
                            class: ImageTool,
                            config: {
                                moxman: moxman,
                                captionPlaceholder: "Nhập mô tả hình ảnh (Tùy chọn)",
                                buttonContent: "Chọn hình ảnh",
                                features: {
                                    background: false,
                                    border: false,
                                    caption: true,
                                    link: true,
                                }
                            }
                        },
                    },
                    data: data_{{ $language['locale'] }},
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
                                    "Large": 'Rộng',
                                    "Normal": "Vừa",
                                    "Small": "Nhỏ",
                                }
                            },
                            toolNames: {
                                Text: "Đoạn văn",
                                Heading: "Tiêu đề đoạn",
                                List: "Danh sách",
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

                // Store instance globally for form submission
                window.editorjsInstance_{{ $language['locale'] }} = editorjs_{{ $language['locale'] }};
            @endforeach

            // Store the primary editor instance (first language) as main instance
            window.editorjsInstance = window.editorjsInstance_{{ config('cnv.languages')[0]['locale'] }};

            // Trigger EditorJS ready event
            $(document).trigger('editorjs:ready');

            // Ensure EditorJS content is ignored by form validation
            $('.longform-content, .ignore-validation').find('input, textarea, select').each(function() {
                $(this).rules('add', {
                    ignoreEditorJS: true
                });
            });
        };

        // Expand editor function
        var expandEditor = async function(locale) {
            currentLocale = locale;

            const editorjsData = await window['editorjsInstance_' + locale].save();

            currentModalData = editorjsData;

            $('#edit-modal').trigger('click');
        };

        // Handle form submission for longform
        var handleLongformSubmit = function() {
            const promises = [];

            @foreach (config('cnv.languages') as $language)
                promises.push(
                    window.editorjsInstance_{{ $language['locale'] }}.save().then((data) => {
                        const dataJson = JSON.stringify(data);
                        $('#editor-content-{{ $language['locale'] }}').val(dataJson);
                    })
                );
            @endforeach

            Promise.all(promises).then(() => {
                $('#save').submit();
            }).catch((error) => {
                console.error('Error saving EditorJS data:', error);
                alert('Please check the content before saving.');
            });
        };

        // Export functions globally
        window.expandEditor = expandEditor;
        window.handleLongformSubmit = handleLongformSubmit;
        window.initializeLongformEditor = initializeLongformEditor;

        // Handle EditorJS validation ignoring for dynamically created content
        $(document).on('DOMNodeInserted', '.longform-content, .ignore-validation', function() {
            var $container = $(this);
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

    })(jQuery);
</script>
