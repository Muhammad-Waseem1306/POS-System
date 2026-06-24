$(function () {
    // Initialize SummerNote
    $(".summerNote").summernote({
        height: 200,
    });

    //Initialize Select2 Elements
    $(".select2").select2();

    // Fix AdminLTE sidebar overlay stuck on page load
    $('.sidebar-overlay, [data-widget="pushmenu"]').each(function() {});
    if ($('body').hasClass('sidebar-open') && $(window).width() < 992) {
        $('body').removeClass('sidebar-open');
    }
    // Remove any lingering overlay on load
    $('.sidebar-overlay').hide();

    // Bootstrap custom file input — show selected filename in label
    $(document).on('change', '.custom-file-input', function () {
        var fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').text(fileName || 'Choose file');
    });
});

function previewThumbnail(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            var thumbnailPreview =
                input.parentNode.querySelector(".thumbnail-preview");
            if (thumbnailPreview) {
                thumbnailPreview.src = e.target.result;
            }
        };

        reader.readAsDataURL(input.files[0]);
    }
}
