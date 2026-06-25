(function (window, document) {
    'use strict';

    var fileDelegateBound = false;

    function updateFormFileState(input) {
        var wrapper = input.closest('[data-form-file]');
        if (!wrapper) {
            return;
        }

        var nameEl = wrapper.querySelector('[data-file-name]');
        if (!nameEl) {
            return;
        }

        var hasFile = !!(input.files && input.files.length);
        nameEl.textContent = hasFile ? input.files[0].name : (nameEl.dataset.defaultName || 'No file chosen');
        wrapper.classList.toggle('form-file--has-file', hasFile);
    }

    function bindFormFileDelegate() {
        if (fileDelegateBound) {
            return;
        }

        fileDelegateBound = true;
        document.addEventListener('change', function (event) {
            if (event.target && event.target.matches && event.target.matches('.form-file__input')) {
                updateFormFileState(event.target);
            }
        });
    }

    function initFormFiles(root) {
        var scope = root || document.getElementById('app-page-root') || document;
        scope.querySelectorAll('[data-form-file] .form-file__input').forEach(function (input) {
            updateFormFileState(input);
        });
    }

    function initImageUploadFields(root) {
        var scope = root || document.getElementById('app-page-root') || document;

        scope.querySelectorAll('.form-image-upload, .image-upload-container').forEach(function (container) {
            if (container.dataset.imageUploadBound === 'true') {
                return;
            }

            var input = container.querySelector('input[type="file"]');
            var preview = container.querySelector('.form-image-upload__img, img.thumbnail-preview, #thumbnailPreview');
            var placeholder = container.querySelector('.form-image-upload__placeholder, .upload-text');

            if (!input) {
                return;
            }

            container.dataset.imageUploadBound = 'true';

            container.addEventListener('click', function (event) {
                if (event.target === input) {
                    return;
                }
                input.click();
            });

            input.addEventListener('change', function () {
                if (!this.files || !this.files[0]) {
                    return;
                }

                var reader = new FileReader();
                reader.addEventListener('load', function () {
                    if (preview) {
                        preview.src = reader.result;
                        preview.classList.remove('d-none');
                    }
                    if (placeholder) {
                        placeholder.classList.add('d-none');
                    }
                });
                reader.readAsDataURL(this.files[0]);
            });
        });
    }

    function initFormModern() {
        bindFormFileDelegate();
        initFormFiles();
        initImageUploadFields();
    }

    window.initFormModern = initFormModern;

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFormModern);
    } else {
        initFormModern();
    }

    document.addEventListener('app:page-loaded', initFormModern);
})(window, document);
