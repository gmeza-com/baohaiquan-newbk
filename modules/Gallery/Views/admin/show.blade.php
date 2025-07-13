@if ($gallery->published === -1)
    <p class="alert alert-danger">
        <strong>Bài viết này đã bị hủy, lý do: </strong> {{ $gallery->cancel_message ?: 'Không rõ' }}
    </p>
@endif

@php
    $content = $gallery->language('content', $locale)->first();
@endphp

<div class="gallery-preview">
    {{-- <div class="gallery-header">
        <h1>{{ $gallery->language('name', $locale) }}</h1>
        @if ($gallery->language('description', $locale))
            <p class="gallery-description">{{ $gallery->language('description', $locale) }}</p>
        @endif
    </div> --}}

    <div class="gallery-content">
        @if ($content)
            <div class="editor-content">
                {!! render_editorjs($content) !!}
            </div>
        @else
            <p class="alert alert-info">Không có nội dung để hiển thị.</p>
        @endif
    </div>
</div>

{{-- <div class="text-right">
    <a href="{{ route('admin.gallery.edit', $gallery->id) }}" class="btn btn-primary">
        <i class="fa fa-pencil"></i> Sửa
    </a>
    
    @if ($gallery->published == 0)
        <a href="javascript:void(0);" class="btn btn-success" onclick="publishGallery({{ $gallery->id }});">
            <i class="fa fa-check"></i> Xuất bản
        </a>
    @endif
</div> --}}
