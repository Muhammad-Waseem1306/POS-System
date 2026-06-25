@props([
    'title',
    'icon' => 'fas fa-cog',
])

<div class="settings-panel__toolbar">
    <h2 class="settings-panel__title">
        <span class="settings-panel__title-icon" aria-hidden="true"><i class="{{ $icon }}"></i></span>
        {{ $title }}
    </h2>
    <button type="submit" class="btn btn-modern btn-modern--primary">
        <i class="fas fa-save" aria-hidden="true"></i>
        Save Changes
    </button>
</div>
