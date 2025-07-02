<link rel="stylesheet" type="text/css" href="/themes/default/style.css" />

@if ($post->published === -1)
    <p class="alert alert-danger">
        <strong>Bài viết này đã bị hủy, lý do: </strong> {{ $post->cancel_message ?: 'Không rõ' }}
    </p>
@endif

<h1 class=" title-news-detail-left ">{{ $post->language('name') }}</h1>

@if ($post->language('second_name'))
    <h2 class="title-news-detail">{{ $post->language('second_name') }}</h2>
@endif

@if ($post->language('third_name'))
    <h3 class="title-news-detail">{{ $post->language('third_name') }}</h3>
@endif

<div class="que_news">
    {!! $post->language('quote')
        ? ($post->prefix ? '<p class="hqol">' . $post->prefix . ' - </p>' : '') . $post->language('quote')
        : '' !!}
</div>

<div class="content_news">
    {!! $post->language('content') !!}

    @if ($post->language('note'))
        <blockquote>
            {!! $post->language('note') !!}
        </blockquote>
    @endif

</div>

<div class="text-right">
    <a href="{{ route('admin.post.edit', $post->id) }}" class="btn btn-primary">
        <i class="fa fa-pencil"></i> Sửa
    </a>

    @if ($post->could_be_approved_post && !$post->approved)
        <a href="javascript:void(0);" class="btn btn-success" onclick="publishedPost({{ $post->id }});">
            <i class="fa fa-check"></i> Duyệt bài này
        </a>
    @endif
</div>
