/**
 * Created by polyanin on 16.11.2016.
 */
// noinspection JSUnusedGlobalSymbols
/**
 *
 * @param data
 * @param append_to
 * @constructor
 */
function AplActionMenu(data, append_to)
{
    append_to = append_to || $('body');
    var instanceName = data.id;

    (
        /**
         * static init
         *
         * @param o self same object
         * @param c same function
         */
        function(o, c) {
            if (undefined === c.instances) {
                c.instances = [];
            }
            if (undefined === c.getInstance) {
                c.getInstance = function(instance_name)
                {
                    if (undefined !== c.instances[instance_name]) {
                        return c.instances[instance_name];
                    }
                    return null;
                };
            }
            if (undefined !== c.instances[instanceName]) {
                console.log('Instance already exists: ' + instanceName);
                console.error('Instance already exists: ' + instanceName);
                throw new Error('Instance already exists: ' + instanceName);
            }
            c.instances[instanceName] = o;
            c.instanceNumber = Object.keys(c.instances).length;
        }
    )(this, arguments.callee);

    var menu = $('<div>').prop({
        id: instanceName,
        class: 'apl-action-menu'
    });
    append_to.append(menu);

    var wrapper = $('<div>').prop({
        class: 'apl-action-menu-wrapper'
    });
    menu.append(wrapper);

    var container = $('<div>').prop({
        class: 'apl-action-menu-container'
    });
    wrapper.append(container);

    var content = $('<div>').prop({
        class: 'apl-action-menu-content'
    });
    container.append(content);

    var scrollbar = $('<div>').prop({
        class: 'apl-action-menu-scrollbar'
    });
    wrapper.append(scrollbar);

    this.show = function () {
        menu.show();
        calcWidth();
        init();
    };

    // noinspection JSUnusedGlobalSymbols
    /**
     * Public method wrapper
     */
    this.hide = function () {
        menu.hide();
    };

    /**
     * Create menu items
     */
    var createMenuItems = function ()
    {
        var items = data.items;
        for (var id in items) {
            // noinspection JSUnfilteredForInLoop
            var item = items[id];
            var span;
            var icon = null;
            if (item.icon !== undefined && item.icon.length) {
                icon = '';
                for (var icon_item_id in item.icon) {
                    icon += '<i class="' + item.icon[icon_item_id] + '" aria-hidden="true"></i>';
                }
                icon = $(icon);
            }
            if (item.url !== undefined) {
                var a = $('<a>');
                a.text(item.name);
                a.prop('href', item.url);
                if (item.hasOwnProperty('target') && item.target) {
                    a.prop('target', item.target);
                }
                content.append(a);
                a.append(icon);
            } else if (item.handler !== undefined) {
                span = $('<span>');
                span.html(item.name);
                content.append(span);
                (function (v)//isolation
                {
                    span.click(function ()
                    {
                        eval(v);
                        menu.hide();
                    });
                })(item.handler);
                span.append(icon);
            } else {
                span = $('<span>');
                span.text(item.name);
                content.append(span);
                span.append(icon);
            }
        }
    };

    createMenuItems();

    var containerHeight;
    var contentHeight;
    var scrollbarHeight;
    var scrollbarTop;
    var skipInit = false;

    var content_scroll_distance;
    var scrollbar_move_distance;

    var margin_right;

    var calcWidth = function () {
        var wrapper_width = wrapper.width();
        var content_width = content.width();
        var scrollbar_width = wrapper_width - content_width;
        margin_right = parseInt(container.css('marginRight'), 10) + (-scrollbar_width);
        container.css({
            marginRight: margin_right
        });
    };

    var init = function() {
        if (skipInit) {
            return;
        }
        containerHeight = container.height();
        contentHeight = content.height();
        if (containerHeight >= contentHeight) {
            scrollbar.hide();
            return;
        }
        scrollbarHeight = containerHeight * containerHeight / contentHeight;
        if (scrollbarHeight < 20) {
            scrollbarHeight = 20;
        }
        content_scroll_distance = contentHeight - containerHeight;
        scrollbar_move_distance = containerHeight - scrollbarHeight;

        scrollbarTop = container.scrollTop() * scrollbar_move_distance / content_scroll_distance;
        scrollbar.css({
            height: scrollbarHeight,
            top: scrollbarTop
        });
        if (margin_right) {
            scrollbar.show();
        }
    };

    $(window).resize(function() {
        init();
        calcWidth();
    });

    container.scroll(function() {
        init();
    });

    init();
}