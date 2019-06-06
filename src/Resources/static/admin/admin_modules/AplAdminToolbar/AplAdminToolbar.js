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
function AplAdminToolbar(data, append_to)
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

    var toolbar = $('<div>').prop({
        id: instanceName,
        class: 'apl-admin-toolbar'
    });
    append_to.append(toolbar);

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
            var text = $('<span class="apl-admin-toolbar-text">' + item.name + '</span>');
            if (item.icon !== undefined && item.icon.length) {
                icon = '<span class="apl-admin-toolbar-icons">';
                for (var icon_item_id in item.icon) {
                    icon += '<span class="apl-admin-toolbar-icon">';
                    icon += '<i class="' + item.icon[icon_item_id] + '" aria-hidden="true"></i></span>';
                }
                icon += '</span>';
                icon = $(icon);
            }
            if (item.url !== undefined) {
                var a = $('<a class="apl-admin-toolbar-item">');
                a.prop('href', item.url);
                if (item.hasOwnProperty('target') && item.target) {
                    a.prop('target', item.target);
                }
                toolbar.append(a);
                a.append(icon);
                a.append(text);
            } else if (item.handler !== undefined) {
                span = $('<span class="apl-admin-toolbar-item">');
                toolbar.append(span);
                (function (v)//isolation
                {
                    span.click(function ()
                    {
                        eval(v);
                    });
                })(item.handler);
                span.append(icon);
                span.append(text);
            } else {
                span = $('<span class="apl-admin-toolbar-item">');
                toolbar.append(span);
                span.append(icon);
                span.append(text);
            }
        }
    };

    createMenuItems();
}