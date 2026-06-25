@props(['size' => 'lg'])

<div {{ $attributes->merge(['class' => 'auth-brand-icon auth-brand-icon--' . $size]) }} aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path
            d="M20 21a8 8 0 1 0-16 0"
            stroke="currentColor" stroke-width="1.75" stroke-linecap="round" />
        <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.75" />
    </svg>
</div>
