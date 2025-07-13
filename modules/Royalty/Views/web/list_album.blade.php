@extends('theme::layout')

@section('content')

    <h1>Albums</h1>

    @foreach($gallery as $g)
        @include('gallery::web.item_album', ['item' => $g])
    @endforeach

    <div class="pagination">
        {!! $gallery->links() !!}
    </div>
@endsection