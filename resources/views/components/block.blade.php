<div class="block {{ isset($footer) ? 'pull' : 'full' }}">
    @if (isset($title))
        <div class="block-title">
            <h3>{{ $title }}</h3>
            @if (isset($action))
                <div class="block-action">
                    {!! $action !!}
                </div>
            @endif
        </div>
    @endif

    {{ $slot }}
</div>
