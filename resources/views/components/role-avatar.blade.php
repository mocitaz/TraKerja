@props(['position' => null])
@php
    $role = \App\Support\JobRoleCatalog::resolve($position);
    $description = $role['matched_role'] ? $role['label'] : 'General role · title not recognized';
@endphp
<span {{ $attributes->class(['tracker-role-avatar']) }} role="img" aria-label="{{ $description }}" title="{{ $description }}">
    <img src="{{ asset($role['asset']) }}" alt="" aria-hidden="true" width="40" height="40" loading="lazy" decoding="async" draggable="false">
</span>
