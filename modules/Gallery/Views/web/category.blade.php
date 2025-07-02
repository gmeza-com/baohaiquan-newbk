@extends('theme::layout')

@section('content')

    <h1>{{ $category->name }}</h1>

    @foreach($gallery as $item)
       @if($item->type == 'video')
           @include('gallery::web.item_album')
       @else
           @include('gallery::web.item_video')
       @endif
    @endforeach

    <div class="pagination">
        {!! $gallery->links() !!}
    </div>
@endsection