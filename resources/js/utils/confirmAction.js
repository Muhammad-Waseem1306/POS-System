import Swal from 'sweetalert2';

export function confirmAction({
    title = 'Confirm',
    text = 'Are you sure?',
    html = null,
    confirmText = 'Confirm',
    cancelText = 'Cancel',
    variant = 'default',
} = {}) {
    return Swal.fire({
        title,
        text: html ? undefined : text,
        html: html || undefined,
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: cancelText,
        buttonsStyling: false,
        reverseButtons: true,
        focusCancel: true,
        customClass: {
            popup: 'app-swal-popup',
            title: 'app-swal-popup__title',
            htmlContainer: 'app-swal-popup__message',
            actions: 'app-swal-popup__actions',
            confirmButton:
                variant === 'danger'
                    ? 'btn btn-modern btn-modern--danger'
                    : 'btn btn-modern btn-modern--primary',
            cancelButton: 'btn btn-modern btn-modern--ghost',
        },
    }).then((result) => result.isConfirmed);
}
