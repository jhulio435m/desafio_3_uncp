@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-uncp-green']) }}>
        {{ $status }}
    </div>
@endif
