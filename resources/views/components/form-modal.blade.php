@props([
    'id',
    'title',
    'action',
    'method' => 'POST',
    'submitLabel' => 'Save',
    'cancelLabel' => 'Cancel',
    'icon' => null,
    'size' => null,
])

<div class="modal fade modal-modern" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div @class([
        'modal-dialog modal-dialog-centered',
        'modal-sm' => $size === 'sm',
        'modal-lg' => $size === 'lg',
    ])>
        <form action="{{ $action }}" method="post" class="modal-content modal-modern__content form-modern">
            @csrf
            @if (strtoupper($method) !== 'POST')
                @method($method)
            @endif

            <div class="modal-modern__header">
                <div class="modal-modern__heading">
                    @if ($icon)
                    <span class="modal-modern__icon" aria-hidden="true">
                        <i class="{{ $icon }}"></i>
                    </span>
                    @endif
                    <h5 class="modal-modern__title" id="{{ $id }}Label">{{ $title }}</h5>
                </div>
                <button type="button" class="modal-modern__close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>

            <div class="modal-modern__body">
                {{ $slot }}
            </div>

            <div class="modal-modern__footer">
                <button type="button" class="btn btn-modern btn-modern--ghost" data-dismiss="modal">
                    {{ $cancelLabel }}
                </button>
                <button type="submit" class="btn btn-modern btn-modern--primary">
                    {{ $submitLabel }}
                </button>
            </div>
        </form>
    </div>
</div>
