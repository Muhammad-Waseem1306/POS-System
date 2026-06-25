function closeAllSidebarSubmenus() {
    var $sidebar = $('.sidebar-modern .nav-sidebar[data-widget="treeview"]');
    if (!$sidebar.length) {
        return;
    }

    $sidebar.children('.nav-item.menu-open').removeClass('menu-open menu-is-opening')
        .children('.nav-treeview').css('display', 'none');
}

window.closeAllSidebarSubmenus = closeAllSidebarSubmenus;

function initSidebarAccordion() {
    var $sidebar = $('.sidebar-modern .nav-sidebar[data-widget="treeview"]');
    if (!$sidebar.length || $sidebar.data('accordion-bound')) {
        return;
    }

    $sidebar.data('accordion-bound', true);

    $sidebar.on('expanded.lte.treeview', function () {
        var $openItems = $sidebar.children('.nav-item.menu-open');
        if ($openItems.length <= 1) {
            return;
        }

        var $keep = $openItems.last();
        $openItems.not($keep).removeClass('menu-open menu-is-opening')
            .children('.nav-treeview').css('display', 'none');
    });

    $sidebar.on('click', '> .nav-item > .nav-link:not(.nav-link--parent)', function () {
        closeAllSidebarSubmenus();
    });

    collapseExtraSidebarMenus();
}

function collapseExtraSidebarMenus() {
    var $sidebar = $('.sidebar-modern .nav-sidebar[data-widget="treeview"]');
    if (!$sidebar.length) {
        return;
    }

    var $openItems = $sidebar.children('.nav-item.menu-open');
    if ($openItems.length <= 1) {
        return;
    }

    var $keep = $openItems.filter(function () {
        return $(this).find('.nav-treeview .nav-link.active').length > 0;
    }).first();

    if (!$keep.length) {
        $keep = $openItems.last();
    }

    $openItems.not($keep).removeClass('menu-open menu-is-opening')
        .children('.nav-treeview').css('display', 'none');
}

function initSelect2(root) {
    var $root = root ? $(root) : $('#app-page-root');
    if (!$root.length) {
        $root = $(document);
    }

    $('body > .select2-dropdown').remove();

    $root.find('select.select2').each(function () {
        var $el = $(this);

        if ($el.hasClass('select2-hidden-accessible')) {
            $el.select2('destroy');
        }

        $el.select2({
            theme: 'bootstrap4',
            width: '100%',
            dropdownCssClass: 'select2-modern-dropdown',
            selectionCssClass: 'select2-modern-selection',
            dropdownAutoWidth: false,
            dropdownParent: $(document.body),
            minimumResultsForSearch: $el.hasClass('form-control-sm') ? Infinity : 0,
        });
    });
}

function initCustomScript() {
    // Initialize SummerNote
    $('#app-page-root .summerNote').each(function () {
        var $el = $(this);
        if ($el.next('.note-editor').length) {
            return;
        }
        $el.summernote({
            height: 200,
        });
    });

    initSelect2('#app-page-root');

    // Fix AdminLTE sidebar overlay stuck on page load
    $('.sidebar-overlay, [data-widget="pushmenu"]').each(function() {});
    if ($('body').hasClass('sidebar-open') && $(window).width() < 992) {
        $('body').removeClass('sidebar-open');
    }
    // Remove any lingering overlay on load
    $('.sidebar-overlay').hide();
}

$(function () {
    initSidebarAccordion();
    initCustomScript();
});

$(document).on('app:page-loaded', initCustomScript);

// Bootstrap custom file input — show selected filename in label
$(document).on('change', '.custom-file-input', function () {
    var fileName = $(this).val().split('\\').pop();
    $(this).siblings('.custom-file-label').text(fileName || 'Choose file');
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
