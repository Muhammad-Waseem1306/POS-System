<x-auth-layout
    pageTitle="Login | {{ readConfig('site_name') }}"
    title="Sign in"
    subtitle="Welcome back! Sign in to access your account."
    illustration="register.svg">

    <form action="{{ route('login') }}" method="post" class="auth-form needs-validation" novalidate>
        @csrf

        <div class="auth-field">
            <label for="email">Email</label>
            <div class="auth-input-wrap">
                <input type="email" class="form-control" id="email" name="email" placeholder="you@company.com"
                    autocomplete="email" required>
            </div>
            <div class="invalid-feedback">Please enter a valid email address.</div>
        </div>

        <div class="auth-field">
            <label for="password">Password</label>
            <div class="auth-input-wrap auth-input-wrap--password">
                <input type="password" class="form-control" id="password" name="password"
                    placeholder="Enter your password" autocomplete="current-password" required>
                <x-auth-password-toggle />
            </div>
            <div class="invalid-feedback">Please enter a password.</div>
        </div>

        <div class="auth-row">
            <label class="auth-checkbox">
                <input type="checkbox" id="rememberMe" name="remember_me">
                <span>Remember me</span>
            </label>
            <a href="{{ route('forget.password') }}" class="auth-link">Forgot password?</a>
        </div>

        <button type="submit" class="auth-btn">Sign In</button>
    </form>

    <x-slot:footer>
        <p>Don't have an account? <a href="{{ route('signup') }}">Sign up</a></p>
    </x-slot:footer>
</x-auth-layout>
