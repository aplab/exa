$(document).ready(function () {

    /**
     * Global object
     *
     * @constructor
     */
    window.AplabAdmin = function () {
    };

    /**
     * Returns common cookie key name
     * @returns {string}
     */
    AplabAdmin.getCookieKey = function () {
        return 'aplab-admin-data';
    };

    /**
     * Initialize
     */
    AplabAdmin.init = function () {
        $('#aplab-admin-open-sidebar').click(function () {
            AplabAdmin.openSidebar();
        });

        $('#aplab-admin-sidebar-button-close').click(function () {
            AplabAdmin.closeSidebar();
        });

        $('#aplab-admin-sidebar-button-toggle-pin').click(function () {
            AplabAdmin.togglePinSidebar();
        });
    };

    /**
     * Open sidebar
     */
    AplabAdmin.openSidebar = function () {
        $('body').addClass('aplab-admin-sidebar-open');
        AplabAdmin.setIsSidebarOpen(true);
        if (AplDataTable.getInstance !== undefined) {
            AplDataTable.getInstance().reinit();
        }
        if (AplInstanceEditor.getInstance !== undefined) {
            AplInstanceEditor.getInstance().fitEditorsSlow();
        }
    };

    /**
     * Close sidebar
     */
    AplabAdmin.closeSidebar = function () {
        $('body').removeClass('aplab-admin-sidebar-open');
        AplabAdmin.setIsSidebarOpen(false);
        if (AplDataTable.getInstance !== undefined) {
            AplDataTable.getInstance().reinit();
        }
        if (AplInstanceEditor.getInstance !== undefined) {
            AplInstanceEditor.getInstance().fitEditorsSlow();
        }
    };

    /**
     * Pin sidebar
     */
    AplabAdmin.pinSidebar = function () {
        $('body').addClass('aplab-admin-sidebar-pin');
        AplabAdmin.setIsSidebarPin(true);
        if (AplDataTable.getInstance !== undefined) {
            AplDataTable.getInstance().reinit();
        }
        if (AplInstanceEditor.getInstance !== undefined) {
            AplInstanceEditor.getInstance().fitEditorsSlow();
        }
    };

    /**
     * Unpin sidebar
     */
    AplabAdmin.unpinSidebar = function () {
        $('body').removeClass('aplab-admin-sidebar-pin');
        AplabAdmin.setIsSidebarPin(false);
        if (AplDataTable.getInstance !== undefined) {
            AplDataTable.getInstance().reinit();
        }
        if (AplInstanceEditor.getInstance !== undefined) {
            AplInstanceEditor.getInstance().fitEditorsSlow();
        }
    };

    /**
     * Toggle pin sidebar
     */
    AplabAdmin.togglePinSidebar = function () {
        if ($('body').hasClass('aplab-admin-sidebar-pin')) {
            AplabAdmin.unpinSidebar();
        } else {
            AplabAdmin.pinSidebar();
        }
    };

    /**
     * Returns cookie stored data
     *
     * @returns {*}
     */
    AplabAdmin.getCookieData = function () {
        var data = Cookies.getJSON(AplabAdmin.getCookieKey());
        var type = typeof(data);
        if ('object' !== type.toLowerCase()) {
            data = {};
            Cookies.set(AplabAdmin.getCookieKey(), data);
        }
        return data;
    };

    /**
     * Set pin sidebar state
     *
     * @param value
     */
    AplabAdmin.setIsSidebarPin = function (value) {
        var data = AplabAdmin.getCookieData();
        data.sidebar_pin = !!value;
        Cookies.set(AplabAdmin.getCookieKey(), data);
    };

    /**
     * Set pin sidebar state
     *
     * @param value
     */
    AplabAdmin.setIsSidebarOpen = function (value) {
        var data = AplabAdmin.getCookieData();
        data.sidebar_open = !!value;
        Cookies.set(AplabAdmin.getCookieKey(), data);
    };

    /**
     * Expand actionsbar handler
     */
    AplabAdmin.expandActionMenu = function ()
    {
        AplActionMenu.getInstance('apl-admin-action-menu').show();
        $('body').on('click', AplabAdmin.clickOutsideActionMenuHandler);
    };

    AplabAdmin.collapseActionMenu = function ()
    {
        $('body').off('click', AplabAdmin.clickOutsideActionMenuHandler);
        AplActionMenu.getInstance('apl-admin-action-menu').hide();
    };

    AplabAdmin.clickOutsideActionMenuHandler = function (event)
    {
        if ($(event.target).closest('#apl-admin-action-menu').length) {
            return;
        }
        AplabAdmin.collapseActionMenu();
    };

    $('#aplab-admin-open-action-menu').on('click', function (event)
    {
        event.stopPropagation();
        AplabAdmin.expandActionMenu();
    });

    /**
     * Show image uploader
     */
    AplabAdmin.showImageUploader = function ()
    {
        var uploader = AplAdminFileUploader.getInstance();
        uploader.setTitle('Upload images only');
        uploader.setUrl('/admin/xhr/uploadImage/');
        uploader.done = function ()
        {
            AplAdminFileUploader.getInstance().purgeWindow();
            AplAdminImageHistory.getInstance().showWindow();
        };
        uploader.showWindow();
    };

    /**
     * Call initialization.
     */
    AplabAdmin.init(/** test 6 */);

    // Expand all dump levels of all sf-dumps on a page.
    $("pre.sf-dump").each(function() {
        $(this).find("a.sf-dump-toggle:gt(0)").each(function(i, a){
            a.click();
        });
    });
});