@extends('backend.master')

@section('title', 'Edit Profile')

@section('content')
<x-form-page
    :action="route('backend.admin.profile.update')"
    :cancel-url="route('backend.admin.dashboard')"
    submit-label="Update Profile"
    enctype="multipart/form-data"
>
    <x-form-section title="Profile Information">
        <x-form-field label="Full Name" name="name" required>
            <input type="text" class="form-control" id="fullName" placeholder="Enter full name" name="name"
                value="{{ $user->name }}" required>
        </x-form-field>
        <x-form-field label="Email" name="email" required>
            <input type="email" class="form-control" id="email" placeholder="Email" name="email"
                value="{{ $user->email }}" required>
        </x-form-field>
        <x-form-field label="Profile Image" name="profile_image" col="md-12">
            <x-form-image-upload
                name="profile_image"
                placeholder="Upload profile image"
                :preview-src="$user->profile_image ? asset('storage/' . $user->profile_image) : null"
            />
        </x-form-field>
    </x-form-section>

    <x-form-section title="Change Password" description="Leave blank to keep your current password.">
        <x-form-field label="Current Password" name="current_password">
            <input type="password" class="form-control" id="password" placeholder="Enter current password"
                name="current_password" autocomplete="new-password">
        </x-form-field>
        <x-form-field label="New Password" name="new_password">
            <input type="password" class="form-control" id="new_password" placeholder="Enter new password"
                name="new_password">
        </x-form-field>
        <x-form-field label="Confirm Password" name="new_password_confirmation">
            <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm new password"
                name="new_password_confirmation">
        </x-form-field>
    </x-form-section>
</x-form-page>
@endsection

@push('script')
<script src="{{ asset('js/image-field.js') }}"></script>
@endpush
