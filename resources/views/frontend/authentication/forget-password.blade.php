<x-auth-layout
    pageTitle="Forget Password | {{ readConfig('site_name') }}"
    title="Forgot password?"
    subtitle="Enter your account email and we'll send you a reset link."
    illustration="register.svg">

    <form action="{{ route('forget.password') }}" method="post" class="auth-form needs-validation" novalidate>
        @csrf

        <div class="auth-field">
            <label for="email">Email</label>
            <div class="auth-input-wrap">
                <input type="email" class="form-control" id="email" name="email" placeholder="you@company.com"
                    autocomplete="email" required>
            </div>
            <div class="invalid-feedback">Enter a valid email address.</div>
        </div>

        <button type="submit" class="auth-btn">Request password reset</button>
    </form>

    <x-slot:footer>
        <p>Back to <a href="{{ route('login') }}">Log in</a></p>
    </x-slot:footer>
</x-auth-layout>
