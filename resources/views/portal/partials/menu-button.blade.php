@props([
    'href' => '#',
    'icon' => 'sparkles',
    'iconBg' => 'bg-gray-100',
    'iconColor' => 'text-gray-700',
    'title' => '',
    'subtitle' => null,
    'disabled' => false,
])

@if ($disabled)
    <div {{ $attributes->merge(['class' => 'menu-btn opacity-60 cursor-not-allowed']) }}>
@else
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'menu-btn']) }}>
@endif
        <div class="menu-icon {{ $iconBg }}">
            <i data-lucide="{{ $icon }}" class="w-6 h-6 {{ $iconColor }}"></i>
        </div>
        <div class="flex-1 text-left">
            <div class="font-bold text-gray-800">{{ $title }}</div>
            @if ($subtitle)
                <div class="text-xs text-gray-500">{{ $subtitle }}</div>
            @endif
        </div>
        @unless ($disabled)
            <i data-lucide="chevron-right" class="w-5 h-5 text-gray-300"></i>
        @endunless
@if ($disabled)
    </div>
@else
    </a>
@endif
