/**
 *
 * @param data
 * @param append_to
 * @constructor
 */
function AplAdminMenu(data, append_to) {
    append_to = append_to || $('body');
    var instanceName = data.id;

    (
        /**
         * static init
         *
         * @param o self o same object
         * @param c same function
         */
        function (o, c) {
            if (undefined === c.instances) {
                c.instances = [];
            }
            if (undefined === c.getInstance) {
                c.getInstance = function (instance_name) {
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

    /**
     * Create menu items
     * @param container
     * @param data
     * @param level
     * @returns {number}
     */
    var createMenuItems = function (container, data, level) {
        level = level || 0;
        var ul = $('<ul>');
        if (0 === level) {
            ul.addClass('apl-admin-menu');
        } else {
            ul.addClass('apl-admin-submenu');
        }
        var counter = 0;
        for (var id in data) {
            counter++;
            // noinspection JSUnfilteredForInLoop
            var item = data[id];
            var li = $('<li>');
            li.prop('id', item.id);
            ul.append(li);
            var span = $('<span>');
            span.text(item.name);
            var icon = null;
            if (item.icon !== undefined && item.icon.length) {
                icon = '';
                for (var icon_item_id in item.icon) {
                    icon += '<i class="' + item.icon[icon_item_id] + '" aria-hidden="true"></i>';
                }
                icon = $(icon);
            }
            var child_number = 0;
            if (item.items !== undefined) {
                child_number = createMenuItems(li, item.items, level + 1);
            }
            if (item.disabled) {
                li.prepend(span);
                span.append(icon);
                continue;
            }
            if (child_number) {
                li.prepend(span);
                span.click(function () {
                    var $this = $(this);
                    var $next = $this.next();
                    var $parent = $this.parent();
                    $next.slideToggle(100);
                    $parent.toggleClass('open');
                    var exclude = append_to.find('.apl-admin-submenu').has($next);
                    append_to.find('.apl-admin-submenu').not($next).not(exclude).slideUp(100).parent().removeClass('open');
                    if ($parent.hasClass('open')) {
                        localStorage.setItem('apl-admin-menu-' + instanceName, $parent.prop('id'));
                        return;
                    }
                    var closest = $parent.closest('.open');
                    if (closest.length) {
                        localStorage.setItem('apl-admin-menu-' + instanceName, closest.prop('id'));
                        return;
                    }
                    localStorage.setItem('apl-admin-menu-' + instanceName, '');
                });
                span.append('<i class="fas fa-chevron-down"></i>');
                if (icon) {
                    span.append(icon);
                }
            } else {
                if (item.url !== undefined) {
                    var a = $('<a>');
                    a.text(item.name);
                    a.prop('href', item.url);
                    if (item.hasOwnProperty('target') && item.target) {
                        a.prop('target', item.target);
                    }
                    li.append(a);
                    a.append(icon);
                } else if (item.handler !== undefined) {
                    li.prepend(span);
                    (function (v)//isolation
                    {
                        span.click(function () {
                            eval(v);
                        });
                    })(item.handler);
                    span.append(icon);
                } else {
                    li.prepend(span);
                    span.append(icon);
                }
            }
        }
        if (counter) {
            container.append(ul);
        }
        return counter;
    };

    createMenuItems(append_to, data.items);

    /**
     * Set current item
     */
    var setCurrent = function () {
        var current_id = localStorage.getItem('apl-admin-menu-' + instanceName);
        if (!current_id) {
            return;
        }
        var current = $('#' + current_id);
        if (!current.length) {
            return;
        }
        current = current.eq(0);
        current.addClass('open').children('.apl-admin-submenu').show();
        append_to.find('.apl-admin-submenu').has(current).show().parent().addClass('open');
    };

    /**
     * Set current item
     */
    setCurrent();

    /**
     * Delay if first run workaround
     */
    setTimeout(function () {
        append_to.find('i.fa-chevron-down').addClass('trans');
    }, 100);

    /**
     * Disable selection
     * @param o
     */
    var disableSelection = function (o) {
        $(o).onselectstart = function () {
            return false;
        };
        $(o).unselectable = "on";
        $(o).css({
            '-moz-user-select': 'none',
            '-khtml-user-select': 'none',
            '-webkit-user-select': 'none',
            '-o-user-select': 'none',
            'user-select': 'none'
        });
    };

    /**
     * Disable selection call
     */
    disableSelection(append_to);
}