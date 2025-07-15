@if($widget->content && $widget->content->count() > 0)
<div class="link-widget link-widget-default">
    @foreach($widget->content->where('active', true)->sortBy('position') as $link)
        @php
            $currentLang = session('lang', config('app.locale', 'vi'));
            $linkData = $link['language'][$currentLang] ?? $link['language']['vi'] ?? [];
        @endphp
        @if(!empty($linkData['image']) && !empty($link['url']))
        <div class="link-item">
            <a href="{{ $link['url'] }}" 
               target="{{ $link['target'] ?? '_self' }}" 
               class="link-wrapper">
                <div class="link-image">
                    <img src="{{ $linkData['image'] }}" alt="Link Image" loading="lazy">
                </div>
            </a>
        </div>
        @endif
    @endforeach
</div>

<style>
.link-widget-default {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 15px;
}

.link-widget-default .link-item {
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.link-widget-default .link-item:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.link-widget-default .link-wrapper {
    display: block;
    text-decoration: none;
}

.link-widget-default .link-image {
    width: 100%;
    height: 120px;
    border-radius: 8px;
    overflow: hidden;
}

.link-widget-default .link-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.link-widget-default .link-item:hover .link-image img {
    transform: scale(1.1);
}

@media (max-width: 768px) {
    .link-widget-default {
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 12px;
    }
    
    .link-widget-default .link-image {
        height: 100px;
    }
}

@media (max-width: 480px) {
    .link-widget-default {
        grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
        gap: 10px;
    }
    
    .link-widget-default .link-image {
        height: 80px;
    }
}
</style>
@endif 