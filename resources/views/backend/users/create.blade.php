@extends('backend.master')

@section('title', 'Create User')

@section('content')
<x-form-page
    :action="route('backend.admin.user.create')"
    :cancel-url="route('backend.admin.users')"
    submit-label="Create User"
    enctype="multipart/form-data"
>
    <x-form-section title="Account Information">
        <x-form-field label="Full Name" name="name" required>
            <input type="text" class="form-control" id="fullName" placeholder="Enter full name" name="name"
                value="{{ old('name') }}" required>
        </x-form-field>
        <x-form-field label="Login Email" name="email" required>
            <input type="email" class="form-control" id="email" placeholder="Enter email address" name="email"
                value="{{ old('email') }}" required>
        </x-form-field>
        <x-form-field label="Role" name="role" required>
            <select class="form-control custom-select" name="role" id="role" required>
                <option value="">Select a role</option>
                @foreach ($roles as $role)
                <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
                @endforeach
            </select>
        </x-form-field>
        <x-form-field label="Login Password" name="password" required>
            <input type="password" class="form-control" id="password" placeholder="Enter password" name="password" required>
        </x-form-field>
        <x-form-field label="Profile Image" name="profile_image" col="md-12">
            <x-form-file name="profile_image" accept="image/*" />
        </x-form-field>
    </x-form-section>
</x-form-page>
@endsection
