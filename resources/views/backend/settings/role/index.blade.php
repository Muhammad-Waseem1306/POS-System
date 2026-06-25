@extends('backend.master')

@section('title', 'Roles')

@section('content')
<x-table-panel title="Roles" icon="fas fa-user-shield" accent="default">
    @can('role_create')
    <x-slot:tools>
        <x-add-new-button data-toggle="modal" data-target="#roleModal" label="Add Role" />
    </x-slot:tools>
    @endcan
    <div class="table-responsive">
        <table class="table table-modern table-hover mb-0">
            <thead>
                <tr>
                    <th>Role</th>
                    <th>Permissions</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                <tr>
                    <td>
                        <span class="role-list__name">{{ $role->name }}</span>
                        @if ($role->id === 1)
                            <span class="badge badge-primary ml-1">System</span>
                        @endif
                    </td>
                    <td>
                        <span class="text-muted">{{ $role->permissions->count() }} enabled</span>
                    </td>
                    <td>
                        <div class="table-actions-inline justify-content-center">
                            @can('role_view')
                            <a title="Manage permissions"
                                href="{{ route('backend.admin.roles.show', $role->id) }}"
                                class="table-actions-btn table-actions-btn--primary">
                                <i class="fas fa-key"></i>
                            </a>
                            @endcan
                            @if ($role->id != 1)
                            @can('role_update')
                            <button title="Edit role" type="button"
                                class="table-actions-btn table-actions-btn--ghost"
                                data-toggle="modal" data-target="#editRole-{{ $role->id }}">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            @endcan
                            @can('role_delete')
                            <a title="Delete role"
                                href="{{ route('backend.admin.roles.delete', $role->id) }}"
                                class="table-actions-btn table-actions-btn--danger"
                                data-confirm="Are you sure you want to delete this role?"
                                data-confirm-title="Delete role"
                                data-confirm-ok="Delete"
                                data-confirm-variant="danger">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                            @endcan
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-table-panel>

@can('role_create')
<x-form-modal
    id="roleModal"
    title="Add Role"
    icon="fas fa-user-shield"
    :action="route('backend.admin.roles.create')"
    submit-label="Create Role">
    <x-form-field label="Role Name" name="name" required col="12">
        <input type="text" class="form-control" id="name" name="name" placeholder="Enter role name" required>
    </x-form-field>
</x-form-modal>
@endcan

@can('role_update')
@foreach ($roles as $role)
@if ($role->id != 1)
<x-form-modal
    id="editRole-{{ $role->id }}"
    title="Edit Role"
    icon="fas fa-pencil-alt"
    :action="route('backend.admin.roles.update', $role->id)"
    method="PUT"
    submit-label="Save Changes">
    <x-form-field label="Role Name" name="name" required col="12">
        <input type="text" class="form-control" name="name"
            value="{{ $role->name }}" placeholder="Enter role name" required>
    </x-form-field>
</x-form-modal>
@endif
@endforeach
@endcan
@endsection
