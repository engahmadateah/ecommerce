@props(['rating' => 0, 'color' => 'text-saffron-500'])
@php $filled = max(0, min(5, (int) round((float) $rating))); @endphp
<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-0.5']) }} role="img" aria-label="{{ $filled }}/5">
    @for ($i = 1; $i <= 5; $i++)
        <i class="{{ $i <= $filled ? 'icon-[ph--star-fill] '.$color : 'icon-[ph--star-fill] text-line' }}"></i>
    @endfor
</span>
