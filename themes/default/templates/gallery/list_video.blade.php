@extends('theme::layout')

@section('content')

    <h1>Video</h1>

    @foreach($gallery as $g)
        @include('gallery::web.item_video', ['item' => $g])
    @endforeach

   {{--  <div class="pagination">
        {!! $gallery->links() !!}
    </div> --}}
@endsection