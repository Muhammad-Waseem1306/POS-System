@extends('backend.master')

@section('title', $role->name . ' — Permissions')
@section('page-class', 'page-modern--permissions')

@section('content')
@php
    $isAdminRole = $role->name === 'Admin';
    $totalPermissions = $permissions->count();
    $assignedCount = $role->permissions->count();
@endphp

<div class="role-permissions-page">
    <div class="form-page__toolbar role-permissions-page__toolbar">
        <a href="{{ route('backend.admin.roles') }}" class="form-page__back">
            <i class="fas fa-arrow-left" aria-hidden="true"></i>
            <span>Back to Roles</span>
        </a>
    </div>

    <div class="content-card role-permissions-page__hero">
        <div class="role-permissions-page__hero-main">
            <span class="role-permissions-page__icon" aria-hidden="true">
                <i class="fas fa-user-shield"></i>
            </span>
            <div>
                <h2 class="role-permissions-page__title">{{ $role->name }}</h2>
                <p class="role-permissions-page__meta">
                    {{ $assignedCount }} of {{ $totalPermissions }} permissions enabled
                </p>
            </div>
        </div>
        @unless($isAdminRole)
        <div class="role-permissions-page__hero-actions">
            <button type="button" class="btn btn-modern btn-modern--ghost btn-modern--sm" id="permission-select-all-global">
                Enable all
            </button>
            <button type="button" class="btn btn-modern btn-modern--ghost btn-modern--sm" id="permission-clear-all-global">
                Disable all
            </button>
        </div>
        @endunless
    </div>

    @if ($isAdminRole)
    <div class="alert alert-info role-permissions-page__notice">
        <i class="fas fa-info-circle" aria-hidden="true"></i>
        The Admin role always has every permission. Changes here are not applied.
    </div>
    @endif

    <form class="form-modern role-permissions-form"
        action="{{ route('backend.admin.update.role-permissions', $role->id) }}"
        method="post"
        id="role-permissions-form">
        @csrf

        @include('backend.settings.partials.permission-groups', [
            'permissionGroups' => $permissionGroups,
            'role' => $role,
            'mode' => 'matrix',
            'disabled' => $isAdminRole,
        ])

        <div class="role-permissions-page__footer">
            <a href="{{ route('backend.admin.roles') }}" class="btn btn-modern btn-modern--ghost">
                Cancel
            </a>
            <button type="submit" class="btn btn-modern btn-modern--primary" @disabled($isAdminRole)>
                <i class="fas fa-save" aria-hidden="true"></i>
                Save Permissions
            </button>
        </div>
    </form>
</div>
@endsection

@push('script')
<script>
(function () {
    function setGroupCheckboxes(groupId, checked) {
        var group = document.getElementById(groupId);
        if (!group) return;
        group.querySelectorAll('.permission-toggle__input:not(:disabled)').forEach(function (input) {
            input.checked = checked;
            input.closest('.permission-toggle').classList.toggle('permission-toggle--active', checked);
        });
    }

    function setAllCheckboxes(checked) {
        document.querySelectorAll('.permission-toggle__input:not(:disabled)').forEach(function (input) {
            input.checked = checked;
            input.closest('.permission-toggle').classList.toggle('permission-toggle--active', checked);
        });
    }

    document.querySelectorAll('.permission-group-select-all').forEach(function (button) {
        button.addEventListener('click', function () {
            setGroupCheckboxes(button.getAttribute('data-target'), true);
        });
    });

    document.querySelectorAll('.permission-group-clear-all').forEach(function (button) {
        button.addEventListener('click', function () {
            setGroupCheckboxes(button.getAttribute('data-target'), false);
        });
    });

    var selectAllGlobal = document.getElementById('permission-select-all-global');
    var clearAllGlobal = document.getElementById('permission-clear-all-global');

    if (selectAllGlobal) {
        selectAllGlobal.addEventListener('click', function () {
            setAllCheckboxes(true);
        });
    }

    if (clearAllGlobal) {
        clearAllGlobal.addEventListener('click', function () {
            setAllCheckboxes(false);
        });
    }

    document.querySelectorAll('.permission-toggle__input').forEach(function (input) {
        input.addEventListener('change', function () {
            input.closest('.permission-toggle').classList.toggle('permission-toggle--active', input.checked);
        });
    });
})();
</script>
@endpush
