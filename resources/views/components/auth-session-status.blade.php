@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'alert alert-ok']) }}>
        <i class="icon-[ph--check-circle-fill] mt-0.5 shrink-0 text-lg"></i>
        <span>{{ $status }}</span>
    </div>
@endif
