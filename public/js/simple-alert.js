(function () {
    function dismissToast(toast) {
        if (!toast || toast.classList.contains('app-toast--dismiss')) {
            return;
        }

        toast.classList.add('app-toast--dismiss');

        window.setTimeout(function () {
            var container = toast.closest('.app-toast-container');
            toast.remove();

            if (container && !container.querySelector('.app-toast')) {
                container.remove();
            }
        }, 250);
    }

    function initToasts() {
        document.querySelectorAll('.app-toast:not([data-toast-init])').forEach(function (toast) {
            toast.setAttribute('data-toast-init', 'true');

            var closeButton = toast.querySelector('.app-toast__close');
            if (closeButton) {
                closeButton.addEventListener('click', function () {
                    dismissToast(toast);
                });
            }

            window.setTimeout(function () {
                dismissToast(toast);
            }, 4500);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initToasts);
    } else {
        initToasts();
    }
})();
