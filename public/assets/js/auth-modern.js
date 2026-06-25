(function () {
    document.querySelectorAll('.auth-toggle-password').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = btn.closest('.auth-input-wrap').querySelector('input');
            var isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            btn.querySelector('.eye-show').classList.toggle('d-none', isHidden);
            btn.querySelector('.eye-hide').classList.toggle('d-none', !isHidden);
        });
    });

    var otpInputs = document.querySelectorAll('.auth-otp input');

    otpInputs.forEach(function (input, index) {
        input.addEventListener('input', function (event) {
            var length = event.target.value.length;

            if (length === 1 && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            } else if (length === 0 && index > 0) {
                otpInputs[index - 1].focus();
            }

            updateOtpStyles();
        });

        input.addEventListener('keydown', function (event) {
            if (event.key === 'Backspace' && input.value.length === 0 && index > 0) {
                otpInputs[index - 1].focus();
            }

            updateOtpStyles();
        });
    });

    function updateOtpStyles() {
        otpInputs.forEach(function (input) {
            input.classList.toggle('filled', input.value.length > 0);
        });
    }
})();
