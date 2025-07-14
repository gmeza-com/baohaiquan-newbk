@php
    $blocks = $data['blocks'] ?? [];
@endphp

<div class="longform-preview-and-edit">


    {{-- <div class="gallery-content">
        @if ($content)
            <div class="editor-content">
                {!! render_editorjs($content) !!}
            </div>
        @else
            <p class="alert alert-info">Không có nội dung để hiển thị.</p>
        @endif
    </div> --}}

    <div id="longform-edit-container" class="longform-edit-container ignore-validation">

    </div>
</div>

{{-- Script cho AJAX-loaded content - SỬ DỤNG SCRIPT THÔNG THƯỜNG --}}
<script>
    $(document).ready(function() {
        const data = @json($data);


        // TODO: Xóa event listener cũ để tránh trùng lặp



        // Define tools for columns
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
        };

        var editorjsInModal = new EditorJS({
            holder: 'longform-edit-container',
            autofocus: true,
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
                        tools: column_tools
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
            data: data,
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
        window.editorjsInModal = editorjsInModal;

        // Trigger EditorJS ready event to apply validation ignore rules
        $(document).trigger('editorjs:ready');

        // Ensure EditorJS content is ignored by form validation
        $('.longform-edit-container, .ignore-validation').find('input, textarea, select').each(function() {
            if ($(this).rules) {
                $(this).rules('add', {
                    ignoreEditorJS: true
                });
            }
        });

        // Handle dynamically created EditorJS elements
        setTimeout(function() {
            $('#longform-edit-container').find(
                'input, textarea, select, [contenteditable], .codex-editor__redactor, .codex-editor__redactor *'
            ).each(function() {
                if ($(this).rules) {
                    $(this).rules('add', {
                        ignoreEditorJS: true
                    });
                }
            });
        }, 1000);

    });
</script>
