(function (window, $) {
    'use strict';

    if (!$ || !$.fn.DataTable) {
        return;
    }

    window.DT_DOM_STANDARD = "<'datatable-toolbar'lf>rt<'datatable-footer'ip>";
    window.DT_DOM_EXPORT = "<'datatable-toolbar'<'datatable-toolbar__left'lB>f>rt<'datatable-footer'ip>";

    var defaultOptions = {
        dom: window.DT_DOM_STANDARD,
        language: {
            lengthMenu: 'Show _MENU_ entries',
            search: '',
            searchPlaceholder: 'Search…',
            info: 'Showing _START_ to _END_ of _TOTAL_ entries',
            infoEmpty: 'Showing 0 to 0 of 0 entries',
            infoFiltered: '(filtered from _MAX_ total entries)',
            zeroRecords: 'No matching records found',
            emptyTable: 'No data available',
            processing: 'Loading…',
            paginate: {
                previous: 'Previous',
                next: 'Next',
            },
        },
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
    };

    window.initModernDataTable = function (selector, options) {
        var $table = $(selector);

        if (!$table.length) {
            return null;
        }

        if ($.fn.DataTable.isDataTable($table)) {
            $table.DataTable().destroy();
        }

        return $table.DataTable($.extend(true, {}, defaultOptions, options || {}));
    };

    window.initModernExportDataTable = function (selector, options) {
        return window.initModernDataTable(selector, $.extend(true, {
            dom: window.DT_DOM_EXPORT,
        }, options || {}));
    };

    document.addEventListener('app:page-loaded', function () {
        document.dispatchEvent(new CustomEvent('datatables:mount'));
        if (window.jQuery) {
            window.jQuery(document).trigger('datatables:mount');
        }
    });
}(window, window.jQuery));
