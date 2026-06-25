<x-auth-layout
    pageTitle="Reset Password | {{ readConfig('site_name') }}"
    title="Password reset"
    subtitle="Please enter the 5-digit code we emailed you."
    illustration="login.svg">

    <form action="{{ route('password.reset') }}" method="post" class="auth-form">
        @csrf

        <div class="auth-otp">
            <input type="text" maxlength="1" name="number_1" inputmode="numeric" autocomplete="one-time-code" required>
            <input type="text" maxlength="1" name="number_2" inputmode="numeric" required>
            <input type="text" maxlength="1" name="number_3" inputmode="numeric" required>
            <input type="text" maxlength="1" name="number_4" inputmode="numeric" required>
            <input type="text" maxlength="1" name="number_5" inputmode="numeric" required>
        </div>

        <button type="submit" class="auth-btn">Continue</button>
    </form>

    <x-slot:footer>
        <p>Didn't receive the email? <a href="{{ route('resend.otp') }}">Resend code</a></p>
        <p>Back to <a href="{{ route('login') }}">Log in</a></p>
    </x-slot:footer>
</x-auth-layout>
