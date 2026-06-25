@php
    $per_found = null;

    if (isset($role)) {
        $per_found = $role->hasPermissionTo($data->name) ? $data->name : null;
    }

    if (isset($user)) {
        $per_found = $user->hasDirectPermission($data->name) ? $data->name : null;
    }

    $isChecked = $data->name == $per_found;
    $inputId = 'permission-' . $data->id;
@endphp

<label class="permission-toggle {{ $isChecked ? 'permission-toggle--active' : '' }}" for="{{ $inputId }}">
    <input type="checkbox"
        class="permission-toggle__input"
        id="{{ $inputId }}"
        name="permissions[]"
        value="{{ $data->name }}"
        @checked($isChecked)
        @if(isset($disabled) && $disabled) disabled @endif>
    <span class="permission-toggle__box" aria-hidden="true">
        <i class="fas fa-check permission-toggle__check"></i>
    </span>
    <span class="permission-toggle__content">
        <span class="permission-toggle__label">{{ snakeToTitle($data->name) }}</span>
        <code class="permission-toggle__slug">{{ $data->name }}</code>
    </span>
</label>
