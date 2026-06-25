@extends('backend.master')

@section('title', 'Permissions')
@section('page-class', 'page-modern--permissions')

@section('content')
@php
    $totalPermissions = $permissions->reject(
        fn ($permission) => in_array($permission->name, config('permissions.excluded_from_permissions_page', []), true)
    )->count();
@endphp

<div class="permissions-catalog-page">
    <div class="content-card permissions-catalog-page__hero">
        <div class="permissions-catalog-page__hero-main">
            <span class="permissions-catalog-page__icon" aria-hidden="true">
                <i class="fas fa-key"></i>
            </span>
            <div>
                <h2 class="permissions-catalog-page__title">Permission Catalog</h2>
                <p class="permissions-catalog-page__meta">
                    {{ $totalPermissions }} permissions grouped by module. Assign them to roles from the Roles page.
                </p>
            </div>
        </div>
        @can('role_view')
        <a href="{{ route('backend.admin.roles') }}" class="btn btn-modern btn-modern--primary">
            <i class="fas fa-user-shield" aria-hidden="true"></i>
            Manage Roles
        </a>
        @endcan
    </div>

    @include('backend.settings.partials.permission-groups', [
        'permissionGroups' => $permissionGroups,
        'mode' => 'list',
    ])
</div>
@endsection
