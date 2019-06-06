/**
 * Created by polyanin on 21.12.2016.
 */
/**
 * @constructor
 */
AplAdminDialog = function (instance_name)
{
    if (AplAdminDialog.instanceExists(instance_name)) {
        throw new Error('Duplicate instance name: "' + instance_name + '"');
    }

    AplAdminDialog.instances[instance_name] = this;

    /**
     * parts
     */
    var dialog, backdrop, container, content, header, body, footer;

    /**
     * retrieve parts
     */
    (function() {
        dialog = $('#' + instance_name).eq(0);
        backdrop = dialog.find('.' + AplAdminDialog.prefix + '-backdrop').eq(0);
        container = dialog.find('.' + AplAdminDialog.prefix + '-container').eq(0);
        content = container.find('.' + AplAdminDialog.prefix + '-content').eq(0);
        header = content.find('.' + AplAdminDialog.prefix + '-header').eq(0);
        body = content.find('.' + AplAdminDialog.prefix + '-body').eq(0);
        footer = content.find('.' + AplAdminDialog.prefix + '-footer').eq(0);
        dialog.css({
            zIndex: ++AplAdminDialog.zIndex
        });
    })();

    /**
     * Returns dialog part
     *
     * @returns {*}
     */
    this.getDialog = function ()
    {
        return dialog;
    };

    /**
     * Returns backdrop part
     *
     * @returns {*}
     */
    this.getBackdrop = function ()
    {
        return backdrop;
    };

    /**
     * Returns container part
     *
     * @returns {*}
     */
    this.getContainer = function ()
    {
        return container;
    };

    /**
     * Returns content part
     *
     * @returns {*}
     */
    this.getContent = function ()
    {
        return content;
    };

    /**
     * Returns header part
     *
     * @returns {*}
     */
    this.getHeader = function ()
    {
        return header;
    };

    /**
     * Returns body part
     *
     * @returns {*}
     */
    this.getBody = function ()
    {
        return body;
    };

    /**
     * Returns footer part
     *
     * @returns {*}
     */
    this.getFooter = function ()
    {
        return footer;
    };

    this.setTitle = function (title)
    {
        var h = this.getHeader();
        h.find('h4.modal-title').text(title);
    };

    /**
     * show window
     */
    this.show = function ()
    {
        dialog.css({
            zIndex: ++AplAdminDialog.zIndex
        }).show();
    };

    /**
     * hide window
     */
    this.hide = function ()
    {
        dialog.hide();
    };

    /**
     * destruct window
     */
    this.purge = function()
    {
        dialog.remove();
        delete AplAdminDialog.instances[instance_name];
    };
};

/**
 * z coordinate of window
 *
 * @type {number}
 */
AplAdminDialog.prefix = 'apl-admin-dialog';

/**
 * z coordinate of window
 *
 * @type {number}
 */
AplAdminDialog.zIndex = 1000000;

/**
 * Instances of window
 *
 * @type {Array}
 */
AplAdminDialog.instances = [];

/**
 * Returns instance by name or false
 *
 * @param instance_name
 * @returns {*|boolean}
 */
AplAdminDialog.getInstance = function (instance_name)
{
    return this.instances[instance_name] || false;
};

/**
 * Returns true if instance exists or false
 *
 * @param instance_name
 * @returns {*|boolean}
 */
AplAdminDialog.instanceExists = function (instance_name)
{
    return undefined !== this.instances[instance_name];
};

/**
 * Create element
 *
 * @param instance_name
 * @param options
 */
AplAdminDialog.createElement = function (instance_name, options)
{
    if (AplAdminDialog.instanceExists(instance_name)) {
        throw new Error('Duplicate instance name: "' + instance_name + '"');
    }
    options = options || {};
    var e = function (c)
    {
        c = c || '';
        if (c.length) {
            c = AplAdminDialog.prefix + '-' + c;
        } else {
            c = AplAdminDialog.prefix;
        }
        return $(document.createElement('div')).addClass(c);
    };
    var dialog = e().prop({
        id: instance_name
    }).css({
        zIndex: ++AplAdminDialog.zIndex
    });
    var backdrop = e('backdrop');
    var container = e('container');
    var content = e('content');
    var header = e('header');
    var body = e('body');
    var footer = e('footer');
    content.append(header);
    content.append(body);
    content.append(footer);
    container.append(content);
    dialog.append(backdrop);
    dialog.append(container);
    if (options.maximize) {
        content.addClass(AplAdminDialog.prefix + '-maximize');
    } else {
        if (options.width && options.height) {
            content.css({
                width: options.width,
                height: options.height
            });
        } else if (options.width) {
            content.css({
                width: options.width
            }).addClass(AplAdminDialog.prefix + '-maximize-height');
        } else if (options.height) {
            content.css({
                height: options.height
            }).addClass(AplAdminDialog.prefix + '-maximize-width');
        } else {
            content.addClass(AplAdminDialog.prefix + '-maximize');
        }
    }
    if (options.hasOwnProperty('button')) {
        var l = options.button.length;
        if (l) {
            for (var i = 0; i < l; i++) {
                footer.append($(options.button[i]));
            }
        }
    }
    if (options.hasOwnProperty('title')) {
        header.append($('<h4 class="modal-title">' + options.title + '</h4>'));
    }
    dialog.appendTo($('body'));
    var o = new AplAdminDialog(instance_name);
    AplAdminDialog.init();
    return o;
};

/**
 * Обработчик кнопки закрытия окна
 *
 * @param event
 */
AplAdminDialog.closeButtonHandler = function (event)
{
    $(event.target).closest('.' + AplAdminDialog.prefix).hide();
};

/**
 * Обработчик кнопки уничтожения окна
 *
 * @param event
 */
AplAdminDialog.purgeButtonHandler = function (event)
{
    var dialog = $(event.target).closest('.' + AplAdminDialog.prefix);
    var id = dialog.prop('id');
    AplAdminDialog.getInstance(id).purge();
};

AplAdminDialog.resizeendTimeout = setTimeout(function ()
{

}, 200);

AplAdminDialog.resizeend = function ()
{
    clearTimeout(AplAdminDialog.resizeendTimeout);
    AplAdminDialog.resizeendTimeout = setTimeout(function ()
    {
        AplAdminDialog.adjustHeightWorkaround();
        console.log('AplAdminDialog.resizeend');
    }, 200);
};

/**
 * Инициализация окон
 */
AplAdminDialog.init = function ()
{
    $('.' + AplAdminDialog.prefix).each(function (i, o)
    {
        o = $(o);
        var id = o.prop('id');
        if (!AplAdminDialog.instanceExists(id)) {
            new AplAdminDialog(id);
        }

        /**
         * close handler
         */
        o.find('.' + AplAdminDialog.prefix + '-close').off(
            'click',
            AplAdminDialog.closeButtonHandler
        ).click(AplAdminDialog.closeButtonHandler);

        /**
         * purge handler
         */
        o.find('.' + AplAdminDialog.prefix + '-purge').off(
            'click',
            AplAdminDialog.purgeButtonHandler
        ).click(AplAdminDialog.purgeButtonHandler);

        $(window).off('resize', AplAdminDialog.resizeend).on('resize', AplAdminDialog.resizeend);
        AplAdminDialog.adjustHeightWorkaround();
    });
};

AplAdminDialog.adjustHeightWorkaround = function ()
{
    var element = $('<div class="' + AplAdminDialog.prefix + '-adjust-height-workaround">');
    $('body').append(element);
    var element_height = element.height();
    element.remove();
    var window_height = window.innerHeight;
    var diff = element_height - window_height;
    if (diff < 1) {
        return;
    }
    var window_width = window.innerWidth;
    if (window_width < 768) {
        $('.' + AplAdminDialog.prefix + '-content').css({
            maxHeight: 'calc(100vh - ' + (20 + diff) + 'px)'
        });
        $('.' + AplAdminDialog.prefix + '-maximize').css({
            height: 'calc(100vh - ' + (20 + diff) + 'px)'
        });
        $('.' + AplAdminDialog.prefix + '-maximize-height').css({
            height: 'calc(100vh - ' + (20 + diff) + 'px)'
        });
        $('.' + AplAdminDialog.prefix + ' .dropdown-menu').css({
            maxHeight: 'calc(100vh - ' + (30 + diff) + 'px)'
        });
        return;
    }
    $('.' + AplAdminDialog.prefix + '-content').css({
        maxHeight: 'calc(100vh - ' + (60 + diff) + 'px)'
    });
    $('.' + AplAdminDialog.prefix + '-maximize').css({
        height: 'calc(100vh - ' + (60 + diff) + 'px)'
    });
    $('.' + AplAdminDialog.prefix + '-maximize-height').css({
        height: 'calc(100vh - ' + (60 + diff) + 'px)'
    });
    $('.' + AplAdminDialog.prefix + ' .dropdown-menu').css({
        maxHeight: 'calc(100vh - ' + (70 + diff) + 'px)'
    });
}

/**
 * init
 */
$(document).ready(function ()
{
    AplAdminDialog.init();
    // AplAdminDialog.createElement('test', {
    //     width: 400,
    //     height: 400,
    //     button: [
    //         $('<button type="button" class="btn btn-success apl-admin-dialog-close">Close</button>')
    //     ]
    // }).show();
    // AplAdminDialog.createElement('test1', {
    //     width: 500,
    //     height: 300,
    //     button: [
    //         $('<button type="button" class="btn btn-info apl-admin-dialog-purge">Close</button>')
    //     ]
    // }).show();
});
