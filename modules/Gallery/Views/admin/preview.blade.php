<div class="gallery-content">
    @if ($data)
        <div class="editor-content">
            {!! render_editorjs($data) !!}
        </div>
    @else
        <p class="alert alert-info">Không có nội dung để hiển thị.</p>
    @endif
</div>
