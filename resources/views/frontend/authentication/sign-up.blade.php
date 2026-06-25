<x-auth-layout
    pageTitle="Sign Up | {{ readConfig('site_name') }}"
    title="Create account"
    subtitle="Sign up now and start managing your store."
    illustration="register.svg">

    <form action="{{ route('signup') }}" method="post" class="auth-form needs-validation" novalidate>
        @csrf

        <div class="auth-field">
            <label for="fullName">Name</label>
            <div class="auth-input-wrap">
                <input type="text" class="form-control" id="fullName" name="name" value="{{ old('name') }}"
                    placeholder="Enter full name" autocomplete="name" required>
            </div>
            <div class="invalid-feedback">Please enter your name.</div>
        </div>

        <div class="auth-field">
            <label for="email">Email</label>
            <div class="auth-input-wrap">
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                    placeholder="you@company.com" autocomplete="email" required>
            </div>
            <div class="invalid-feedback">Please enter a valid email address.</div>
        </div>

        <div class="auth-field">
            <label for="password">Password</label>
            <div class="auth-input-wrap auth-input-wrap--password">
                <input type="password" class="form-control" id="password" name="password"
                    placeholder="Create a password" autocomplete="new-password" required>
                <x-auth-password-toggle />
            </div>
            <div class="invalid-feedback">Please enter a password.</div>
        </div>

        <div class="auth-field">
            <label for="confirmPassword">Confirm password</label>
            <div class="auth-input-wrap auth-input-wrap--password">
                <input type="password" class="form-control" id="confirmPassword" name="password_confirmation"
                    placeholder="Confirm your password" autocomplete="new-password" required>
                <x-auth-password-toggle />
            </div>
            <div class="invalid-feedback">Please confirm your password.</div>
        </div>

        <div class="auth-field">
            <label class="auth-checkbox">
                <input type="checkbox" id="agree" name="remember" required>
                <span>I agree to all the <a href="#">Terms</a> and <a href="#">Privacy Policy</a>.</span>
            </label>
            <div class="invalid-feedback">Please agree to our terms.</div>
        </div>

        <button type="submit" class="auth-btn">Create account</button>
    </form>

    <x-slot:footer>
        <p>Already have an account? <a href="{{ route('login') }}">Log in</a></p>
    </x-slot:footer>
</x-auth-layout>
