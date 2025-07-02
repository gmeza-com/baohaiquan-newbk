@if($breadcrumb)
<ol class="breadcrumb">
    <li><a href="{{ url('/') }}">{{ trans('language.home') }}</a></li>
    @foreach($breadcrumb as $item)
        @if(!$loop->last)
        <li>
            <a href="{{ $item['link'] }}">{{ $item['name'] }}</a>
        </li>
        @endif
    @endforeach
    @if(@$post)
        <li>
            <a href="{{ $item['link'] }}">{{ @$post->post->published_at->format('d/m/Y H:i:s') }}</a>
        </li>
        <li>
            <?php
                $map = ["3014" => 4888, "17933" => 4888, "17934" => 4888, "17935" => 4888, "20168" => 8127, "20167" => 7845];
                $ingress = [3014, 17933, 17935, 17934, 17935];
            ?>
           <span><i class="fa fa-eye" aria-hidden="true"></i> {{ @number_format( isset($map["{$post->post_id}"]) ?  $post->post->view->count + $map["{$post->post_id}"] : $post->post->view->count) }} </span>
        </li>
        <li class="printx">
            <a onclick="printpop();">
            <img width="12" height="12" src="{{$theme_url}}/images/printer.png"></a>
        </li>
    @endif

</ol>
@endif
@push('footer')
<script>
function printpop(){
    var a = window.location.href,
        a = a.replace("/tin-tuc/", "/print/");
    window.open(a, "_blank", "left=300,top=0,width=550,height=600,toolbar=0,scrollbars=1,status=0");
}
</script>
@endpush
