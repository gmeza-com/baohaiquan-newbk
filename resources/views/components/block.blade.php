<div class="block {{ isset($footer) ? 'pull' : 'full' }}">
    @if(isset($title))
    <div class="block-title">
        @if(isset($action))
            <div class="block-action">
                {!! $action !!}
            </div>
        @endif

        <h2>{{ $title }}</h2>
    </div>
    @endif

    {{ $slot }}
</div>
