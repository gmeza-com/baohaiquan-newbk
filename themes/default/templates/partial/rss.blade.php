@push('footer')
<script type="text/javascript">
    $("#link").change(function(e)
    {
        window.open($(this).val());
    });
</script>
@endpush
<aside class="rss-widget widget">
    <h3 class="widget-title">
        <span class="icon"></span><span>liên kết website</span></h3>

    <div class="">
        <a href="http://www.mod.gov.vn/" target="_blank">
            <img src="/storage/images/banner-bqp.png">
        </a>
    </div>
    <br>
    <div class="selectbox">
        @php $form = \Modules\Form\Models\Form::whereSlug('direct-link-website')->firstOrFail(); @endphp
        @foreach($form->field as $field)
        @if($field['type'] == 'select')
            <select name="{{ $field['slug'] }}" class="form-control non-select2" id="link">
                <option value="0">---- Chọn website ----</option>
                @foreach(explode(PHP_EOL, $field['option']) as $option)
                    <option value="http://{{ $option }}">{{ $option }}</option>
                @endforeach
            </select>
        @endif
        @endforeach
    </div>
</aside><!-- rss-widget -->

