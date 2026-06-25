<x-auth-layout
    pageTitle="New Password | {{ readConfig('site_name') }}"
    title="Reset password"
    subtitle="Choose a strong new password for your account."
    illustration="register.svg">

    <form action="{{ route('new.password') }}" method="post" class="auth-form needs-validation" id="resetPasswordForm"
        novalidate>
        @csrf

        <div class="auth-field">
            <label for="password">Password</label>
            <div class="auth-input-wrap auth-input-wrap--password">
                <input type="password" class="form-control" id="password" name="password"
                    placeholder="Enter new password" autocomplete="new-password" required>
                <x-auth-password-toggle />
            </div>
            <div class="invalid-feedback" id="passwordValidationText">Enter password.</div>
        </div>

        <div class="auth-field">
            <label for="confirmPassword">Confirm password</label>
            <div class="auth-input-wrap auth-input-wrap--password">
                <input type="password" class="form-control" id="confirmPassword" name="password_confirmation"
                    placeholder="Confirm new password" autocomplete="new-password" required>
                <x-auth-password-toggle />
            </div>
            <div class="invalid-feedback" id="confirmPasswordValidationText"></div>
        </div>

        <button type="submit" class="auth-btn">Reset password</button>
    </form>

    <x-slot:footer>
        <p>Back to <a href="{{ route('login') }}">Log in</a></p>
    </x-slot:footer>
</x-auth-layout>
