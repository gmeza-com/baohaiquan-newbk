@extends('theme::layout')

@section('content')
    <section id="include">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    @include('theme::partial.breadcum')
                    <h1>{{ $category->name }}</h1>
                    @php
                        $item = $gallery->first();
                        $featured = get_list_gallery(1, $category->id, true, 'featured')->first();
                        // if (! $featured) {
                        //    $featured = $latestPosts->first();
                        //    $featured = $item->reject(function($item, $index) use ($featured) {
                        //         return $featured->id == $item->id;
                        //    });
                        // }
                    @endphp
                    @if ($item->type == 'video')
                        @include('theme::gallery.item_album')
                    @else
                        @include('theme::gallery.item_video')
                    @endif
                    <div class="title_other" style="position:relative">
                        <h2 class="widget-title"><span class="icon"></span><span>Video Liên Quan</span></h2>
                    </div>

                    @include('theme::partial.other_video')

                    <!-- <div class="col-sm-12 text-center" style="margin-bottom: 20px;">
                                  {!! $gallery->links() !!}
                              </div> -->
                </div>
                <div class="col-sm-4 navleft destop-pr0 tablet-pr0">
                    @include('theme::partial.slidebar')
                </div>
            </div>
        </div>
    </section>
@endsection
