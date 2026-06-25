(function (window, document) {
    var dialog = null;
    var titleEl = null;
    var messageEl = null;
    var iconEl = null;
    var confirmBtn = null;
    var cancelBtn = null;
    var pendingResolve = null;

    var icons = {
        default: 'fas fa-question',
        danger: 'fas fa-exclamation-triangle',
        warning: 'fas fa-exclamation-circle',
    };

    function getDialog() {
        if (dialog) {
            return dialog;
        }

        dialog = document.getElementById('app-confirm-dialog');
        if (!dialog) {
            return null;
        }

        titleEl = document.getElementById('appConfirmTitle');
        messageEl = document.getElementById('appConfirmMessage');
        iconEl = dialog.querySelector('.app-confirm__icon i');
        confirmBtn = document.getElementById('appConfirmOk');
        cancelBtn = document.getElementById('appConfirmCancel');

        confirmBtn.addEventListener('click', function () {
            finish(true);
        });

        cancelBtn.addEventListener('click', function () {
            finish(false);
        });

        if (window.jQuery) {
            window.jQuery(dialog).on('hidden.bs.modal', function () {
                if (pendingResolve) {
                    finish(false);
                }
            });
        }

        return dialog;
    }

    function finish(result) {
        var resolve = pendingResolve;
        pendingResolve = null;

        if (window.jQuery && dialog) {
            window.jQuery(dialog).modal('hide');
        }

        if (resolve) {
            resolve(!!result);
        }
    }

    function applyVariant(variant) {
        if (!dialog || !confirmBtn || !iconEl) {
            return;
        }

        dialog.classList.remove('app-confirm--danger', 'app-confirm--warning');
        confirmBtn.classList.remove('btn-modern--danger', 'btn-modern--primary');

        if (variant === 'danger') {
            dialog.classList.add('app-confirm--danger');
            confirmBtn.classList.add('btn-modern--danger');
            iconEl.className = icons.danger;
            return;
        }

        if (variant === 'warning') {
            dialog.classList.add('app-confirm--warning');
            confirmBtn.classList.add('btn-modern--primary');
            iconEl.className = icons.warning;
            return;
        }

        confirmBtn.classList.add('btn-modern--primary');
        iconEl.className = icons.default;
    }

    function ask(options) {
        options = options || {};

        return new Promise(function (resolve) {
            if (!getDialog()) {
                resolve(window.confirm(options.message || 'Are you sure?'));
                return;
            }

            pendingResolve = resolve;

            titleEl.textContent = options.title || 'Confirm';
            messageEl.textContent = options.message || 'Are you sure?';
            confirmBtn.textContent = options.confirmText || 'Confirm';
            cancelBtn.textContent = options.cancelText || 'Cancel';

            applyVariant(options.variant || 'default');

            if (window.jQuery) {
                window.jQuery(dialog).modal('show');
            }
        });
    }

    function readOptions(el) {
        return {
            title: el.getAttribute('data-confirm-title') || 'Confirm',
            message: el.getAttribute('data-confirm') || 'Are you sure?',
            confirmText: el.getAttribute('data-confirm-ok') || 'Confirm',
            cancelText: el.getAttribute('data-confirm-cancel') || 'Cancel',
            variant: el.getAttribute('data-confirm-variant') || 'default',
        };
    }

    function bindDelegatedEvents() {
        document.addEventListener('click', function (event) {
            var trigger = event.target.closest('[data-confirm]');

            if (!trigger || trigger.disabled || trigger.classList.contains('disabled')) {
                return;
            }

            if (trigger.tagName === 'FORM') {
                return;
            }

            if (trigger.matches('input[type="submit"], button[type="submit"]')) {
                var form = trigger.closest('form[data-confirm]');
                if (form) {
                    return;
                }
            }

            event.preventDefault();

            ask(readOptions(trigger)).then(function (confirmed) {
                if (!confirmed) {
                    return;
                }

                if (trigger.tagName === 'A') {
                    if (window.AppNavigation && window.AppNavigation.navigate) {
                        window.AppNavigation.navigate(trigger.href);
                        return;
                    }

                    window.location.href = trigger.href;
                    return;
                }

                if (trigger.tagName === 'BUTTON' && trigger.type === 'submit') {
                    var parentForm = trigger.closest('form');
                    if (parentForm) {
                        parentForm.dataset.confirmed = 'true';
                        parentForm.requestSubmit(trigger);
                    }
                    return;
                }

                trigger.click();
            });
        });

        document.addEventListener('submit', function (event) {
            var form = event.target;

            if (!form.matches('[data-confirm]')) {
                return;
            }

            if (form.dataset.confirmed === 'true') {
                delete form.dataset.confirmed;
                return;
            }

            event.preventDefault();

            ask(readOptions(form)).then(function (confirmed) {
                if (!confirmed) {
                    return;
                }

                if (window.AppNavigation && window.AppNavigation.applyHtml && form.closest('#app-page-root')) {
                    var formData = new FormData(form);

                    fetch(form.action, {
                        method: (form.method || 'POST').toUpperCase(),
                        body: formData,
                        credentials: 'same-origin',
                        headers: {
                            Accept: 'text/html',
                            'X-Partial-Navigation': '1',
                        },
                    })
                        .then(function (response) {
                            if (!response.ok) {
                                throw new Error('Submit failed');
                            }

                            var finalUrl = response.url;
                            return response.text().then(function (html) {
                                return window.AppNavigation.applyHtml(html, finalUrl);
                            });
                        })
                        .catch(function () {
                            form.dataset.confirmed = 'true';
                            form.requestSubmit();
                        });

                    return;
                }

                form.dataset.confirmed = 'true';
                form.requestSubmit();
            });
        });
    }

    window.AppConfirm = {
        ask: ask,
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bindDelegatedEvents);
    } else {
        bindDelegatedEvents();
    }
})(window, document);
