@php
    $toasts = collect();

    if (isset($errors) && $errors->any()) {
        $toasts->push([
            'type' => 'error',
            'label' => 'Error',
            'icon' => 'fas fa-times',
            'message' => $errors->all(),
        ]);
    }

    if (session()->has('success')) {
        $toasts->push([
            'type' => 'success',
            'label' => 'Success',
            'icon' => 'fas fa-check',
            'message' => [session('success')],
        ]);
    }

    if (session()->has('error')) {
        $toasts->push([
            'type' => 'error',
            'label' => 'Error',
            'icon' => 'fas fa-times',
            'message' => [session('error')],
        ]);
    }

    if (session()->has('warning')) {
        $toasts->push([
            'type' => 'warning',
            'label' => 'Warning',
            'icon' => 'fas fa-exclamation',
            'message' => [session('warning')],
        ]);
    }
@endphp

@if ($toasts->isNotEmpty())
<div class="app-toast-container" role="region" aria-label="Notifications">
    @foreach ($toasts as $toast)
    <div class="app-toast app-toast--{{ $toast['type'] }}" role="alert" aria-live="assertive">
        <span class="app-toast__icon" aria-hidden="true">
            <i class="{{ $toast['icon'] }}"></i>
        </span>
        <div class="app-toast__body">
            <span class="app-toast__label">{{ $toast['label'] }}</span>
            <div class="app-toast__message">
                @foreach ($toast['message'] as $line)
                    <p>{{ $line }}</p>
                @endforeach
            </div>
        </div>
        <button type="button" class="app-toast__close" aria-label="Dismiss notification">
            <i class="fas fa-times" aria-hidden="true"></i>
        </button>
        <span class="app-toast__progress" aria-hidden="true"></span>
    </div>
    @endforeach
</div>
<script src="{{ asset('js/simple-alert.js') }}?v=2"></script>
@endif
