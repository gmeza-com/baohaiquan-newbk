@if(@$category)
@foreach($gallery as $other)
  <div class="col-md-4 col-xs-6 gallery">
    <div class="thumbnail video">
      <a href="{{ $other->language('link') }}" title="{{ $other->language('name') }}">
              <img src="{{ $other->thumbnail }}" class="video-btn">
      <div class="mask_play"></div>
      </a>
    </div>
    <h4 class="text-center">
      <b>
        <a href="{{ $other->language('link') }}" title="{{ $other->language('name') }}">{{ $other->language('name') }}</a>
      </b>
  </h4>
  </div>
@endforeach
<div class="col-sm-12 text-center" style="margin-bottom: 20px;">
    {!! $gallery->links() !!}
</div>
@else
@foreach(get_list_gallery(9,$category_id,true) as $other)
  <div class="col-md-4 col-xs-6 gallery">
    <div class="thumbnail video">
      <a href="{{ $other->language('link') }}" title="{{ $other->language('name') }}">
              <img src="{{ $other->thumbnail }}" class="video-btn">
      <div class="mask_play"></div>
      </a>
    </div>
    <h4 class="text-center">
      <b>
        <a href="{{ $other->language('link') }}" title="{{ $other->language('name') }}">{{ $other->language('name') }}</a>
      </b>
  </h4>
  </div>
@endforeach
@endif
<div class="col-sm-12 text-center" style="margin-bottom: 20px;">
    {{-- {!! $gallery->links() !!} --}}
</div>
