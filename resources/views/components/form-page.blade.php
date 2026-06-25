@props([
    'action',
    'method' => 'POST',
    'cancelUrl' => null,
    'submitLabel' => 'Create',
    'enctype' => null,
])

<div class="content-card form-page">
    @if ($cancelUrl)
        <div class="form-page__toolbar">
            <a href="{{ $cancelUrl }}" class="form-page__back">
                <i class="fas fa-arrow-left" aria-hidden="true"></i>
                <span>Back to list</span>
            </a>
        </div>
    @endif

    <form
        action="{{ $action }}"
        method="{{ in_array(strtoupper($method), ['GET']) ? 'get' : 'post' }}"
        class="form-page__form accountForm form-modern"
        @if ($enctype) enctype="{{ $enctype }}" @endif
        {{ $attributes }}
    >
        @csrf
        @if (! in_array(strtoupper($method), ['GET', 'POST']))
            @method($method)
        @endif

        <div class="form-page__body">
            {{ $slot }}
        </div>

        @isset($actions)
            {{ $actions }}
        @else
            <x-form-actions :cancel-url="$cancelUrl" :submit-label="$submitLabel" />
        @endisset
    </form>
</div>
