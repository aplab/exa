/**
 * Created by polyanin on 06.12.2016.
 */
/**
 * Created by polyanin on 17.11.2016.
 */
function AplInstanceEditor(container) {

    (
        /**
         * static init
         *
         * @param o self same object
         * @param c same function
         */
        function (o, c) {
            if (undefined === c.instance) {
                c.instance = o;
            } else {
                if (c.instance !== o) {
                    console.log('Instance already exists. Only one instance allowed!');
                    throw new Error('Instance already exists. Only one instance allowed!');
                }
            }
            if (undefined === c.getInstance) {
                c.getInstance = function () {
                    return c.instance;
                };
            }
        }
    )(this, arguments.callee);

    var prefix = '.apl-instance-editor-';
    var class_prefix = 'apl-instance-editor-';

    var tabs = container.find(prefix + 'tabs').eq(0);
    var tabs_wrapper = container.find(prefix + 'tabs-wrapper').eq(0);
    var head = container.find(prefix + 'head').eq(0);
    var body = container.find(prefix + 'body').eq(0);
    var arrow_left = container.find(prefix + 'arrow-left').eq(0);
    var arrow_right = container.find(prefix + 'arrow-right').eq(0);

    var tabs_width = [];
    var tabs_scroll = [];
    var tabs_width_sum = 0;
    var local_storage_key = class_prefix + md5(window.location);
    var default_tab_index = 0;
    var default_tabs_wrapper_scroll = 0;

    var loadState = function () {
        var raw = localStorage.getItem(local_storage_key);
        var json = JSON.parse(raw);
        try {
            var i = parseInt(json.i);
            if (isNaN(i)) {
                default_tab_index = 0;
            } else {
                default_tab_index = i;
            }
            var s = parseInt(json.s);
            if (isNaN(s)) {
                default_tabs_wrapper_scroll = 0;
            } else {
                default_tabs_wrapper_scroll = s;
            }
        } catch (err) {
            console.log(err)
        }
    };

    var saveState = function () {
        localStorage.setItem(local_storage_key, JSON.stringify({
            i: default_tab_index,
            s: default_tabs_wrapper_scroll,
        }));
    };

    loadState();

    /**
     * Calculate width
     */
    var recalcWidth = function () {
        if (head.width() < tabs_width_sum) {
            if (tabs_wrapper.scrollLeft() < 1) {
                arrow_left.hide();
            } else {
                arrow_left.show();
            }
            var check = head.width() + tabs_wrapper.scrollLeft();
            if ((tabs_width_sum - check) < 1) {
                arrow_right.hide();
            } else {
                arrow_right.show();
            }
        } else {
            arrow_left.hide();
            arrow_right.hide();
            tabs_wrapper.css({
                left: 0,
                right: 0
            })
        }
    };

    /**
     * Initialization
     */
    var init = function () {
        tabs_wrapper.scrollLeft(default_tabs_wrapper_scroll);
        var tabs_width_sum_tmp = 0;
        var found_tab = tabs.find(prefix + 'tab');
        if (default_tab_index > found_tab.length) {
            default_tab_index = 0;
        }
        found_tab.each(function (i, o) {
            tabs_width[i] = $(o).outerWidth(true);
            tabs_scroll[i] = tabs_width_sum_tmp;
            tabs_width_sum_tmp += tabs_width[i];
        });
        if (!tabs_width_sum) {
            found_tab.removeClass(class_prefix + 'tab-active')
                .eq(default_tab_index).addClass(class_prefix + 'tab-active');
        }
        tabs_width_sum_tmp += .3;
        if (!tabs_width_sum) {
            // first run only
            body.find(prefix + 'panel')
                .removeClass(class_prefix + 'panel-active')
                .eq(default_tab_index).addClass(class_prefix + 'panel-active');
        }
        tabs.css({
            width: tabs_width_sum_tmp
        });
        tabs_width_sum = tabs_width_sum_tmp;
        recalcWidth();
    };

    /**
     * Window resize handler
     */
    $(window).resize(function () {
        tabs_wrapper.scrollLeft(tabs_scroll[default_tab_index] - (tabs_wrapper.width() - tabs_width[default_tab_index]) / 2);
        default_tabs_wrapper_scroll = tabs_wrapper.scrollLeft();
    });

    /**
     * Window resizeend handler
     */
    $(window).on('resizeend', function () {
        recalcWidth();
        saveState();
    });

    /**
     * Tabs click handler
     */
    tabs.find(prefix + 'tab').click(function () {
        var all = tabs.find(prefix + 'tab');
        $(this).addClass(class_prefix + 'tab-active');
        all.not(this).removeClass(class_prefix + 'tab-active');
        var index = all.index(this);
        default_tab_index = index;
        // trying to scroll tabs wrapper so that the selected tab is in the center
        tabs_wrapper.scrollLeft(tabs_scroll[index] - (tabs_wrapper.width() - tabs_width[index]) / 2);
        default_tabs_wrapper_scroll = tabs_wrapper.scrollLeft();
        body.find(prefix + 'panel')
            .removeClass(class_prefix + 'panel-active')
            .eq(index).addClass(class_prefix + 'panel-active');
        saveState();
        recalcWidth();
    });

    /**
     * Left arrow click handler
     */
    arrow_left.click(function () {
        tabs_wrapper.scrollLeft(tabs_wrapper.scrollLeft() - 50);
        default_tabs_wrapper_scroll = tabs_wrapper.scrollLeft();
        recalcWidth();
        saveState();
    });

    /**
     * Right arrow click handler
     */
    arrow_right.click(function () {
        tabs_wrapper.scrollLeft(tabs_wrapper.scrollLeft() + 50);
        default_tabs_wrapper_scroll = tabs_wrapper.scrollLeft();
        recalcWidth();
        saveState();
    });

    // workaround
    (function() {
        for (var i = 0; i < 100; i += 10) {
            setTimeout(function () {
                init();
            }, (100 + i) * i);
        }
    })();

    recalcWidth();

    if (typeof(CKEDITOR) != 'undefined') {
        /**
         * CKEditor initialization
         *
         * @param config
         */
        CKEDITOR.editorConfig = function (config) {
            // Define changes to default configuration here. For example:
            // config.language = 'fr';
            config.uiColor = 'f2f1f0';
            config.resize_enabled = false;
            config.toolbarCanCollapse = true;
            config.removePlugins = 'about,maximize';
            config.height = 10000;
            config.allowedContent = true;
        };
    }

    /**
     * adjust editor size
     */
    var fitEditors = function () {
        var height = body.height();
        var width = body.width();
        for (var o in CKEDITOR.instances) {
            if (CKEDITOR.instances.hasOwnProperty(o)) {
                CKEDITOR.instances[o].resize(width, height);
            }
        }
    };
    
    /**
     * CKEditor size adjust
     */
    this.fitEditors = function () {
        fitEditors();
    };
    
    this.fitEditorsSlow = function () {
        for (var i = 0; i < 5; i++) {
            setTimeout(function () {
                fitEditors();
            }, (100 + i) * i);
        }
    };

    /**
     * Init CKEditors
     */
    if (typeof(CKEDITOR) != 'undefined') {
        CKEDITOR.on('instanceReady', function (ev) {
            var editor = ev.editor;
            var height = body.height();
            var width = body.width();
            editor.resize(width, height);
            editor.on('afterCommandExec', function () {
                var width = body.width();
                var height = body.height();
                editor.resize(width, height);
            });
            $(window).resize(function () {
                fitEditors();
            });


            tabs.find(prefix + 'tab').click(function () {
                fitEditors();
            });
        });
    }

    /**
     * Workaround for small devices
     *
     * @returns {boolean}
     */
    var is_small = function () {
        var width = $(window).width();
        var height = $(window).height();
        var threshold = 768;
        return width <= threshold && height <= threshold;
    };

    /**
     * Editor configuration
     *
     * @returns {{uiColor: string, removePlugins: string, resize_enabled: boolean, height: number, removeButtons: string}}
     */
    var editor_config = function () {
        var config = {
            uiColor: '#ffffff',
            // removePlugins: 'about,maximize',
            removePlugins: 'maximize',
            resize_enabled: false,
            height: 10000,
            removeButtons: 'Cut,Copy,Scayt'
        };
        // Define changes to default configuration here.
        // For complete reference see:
        // http://docs.ckeditor.com/#!/api/CKEDITOR.config
        // Set the most common block elements.
        config.format_tags = 'p;h1;h2;h3;pre';
        // Simplify the dialog windows.
        // config.removeDialogTabs = 'image:advanced;link:advanced';
        if (is_small()) {
            // The toolbar groups arrangement, optimized for a single toolbar row.
            config.toolbar = [
                {name: 'document', items: ['Source']},
                {name: 'clipboard', items: ['Undo', 'Redo']},
                {name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript']},
                {
                    name: 'paragraph',
                    items: ['NumberedList', 'BulletedList', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']
                },
                {name: 'links', items: ['Link', 'Unlink']},
                {name: 'insert', items: ['Image', 'Table', 'Smiley', 'SpecialChar']},
                {name: 'colors', items: ['TextColor', 'BGColor']}
            ];
            // The default plugins included in the basic setup define some buttons that
            // are not needed in a basic editor. They are removed here.
            config.removeButtons = 'Cut,Copy,Paste,Undo,Redo,Anchor,Underline,Strike,Subscript,Superscript';
            // Dialog windows are also simplified.
            config.removeDialogTabs = 'link:advanced';
            config.toolbarStartupExpanded = false;
            config.toolbarCanCollapse = true;
        } else {
            config.toolbar = [
                {name: 'document', items: ['Source']},
                {name: 'clipboard', items: ['Paste', 'PasteText', 'PasteFromWord', 'Undo', 'Redo']},
                {name: 'editing', items: ['Find', 'Replace', 'SelectAll']},
                {
                    name: 'basicstyles',
                    items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', 'CopyFormatting', 'RemoveFormat']
                },
                {
                    name: 'paragraph',
                    items: ['NumberedList', 'BulletedList', 'Outdent', 'Indent', 'Blockquote', 'CreateDiv', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']
                },
                {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
                {name: 'colors', items: ['TextColor', 'BGColor']},
                {name: 'tools', items: ['ShowBlocks']},
                {
                    name: 'insert',
                    items: ['Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe']
                },
                {name: 'styles', items: ['Styles', 'Format', 'FontSize']},
                {name: 'about', items: ['About']}
            ];
            config.toolbarCanCollapse = false;
            config.toolbarStartupExpanded = true;
        }
        return config;
    };

    /**
     * Save handler
     */
    this.save = function () {
        body.children('form').eq(0).submit();
    };

    /**
     * Save and exit handler
     */
    this.saveAndExit = function () {
        var e = document.createElement('input');
        $(e).attr({
            'type': 'hidden',
            'name': 'saveAndExit',
            'value': 'Y'
        });
        body.children('form').eq(0).append(e);
        this.save();
    };

    /**
     * Save and add new handler
     */
    this.saveAndAdd = function () {
        var e = document.createElement('input');
        $(e).attr({
            'type': 'hidden',
            'name': 'saveAndAdd',
            'value': 'Y'
        });
        body.children('form').eq(0).append(e);
        this.save();
    };

    /**
     * Save as new handler
     */
    this.saveAsNew = function () {
        var e = document.createElement('input');
        $(e).attr({
            'type': 'hidden',
            'name': 'saveAsNew',
            'value': 'Y'
        });
        body.children('form').eq(0).append(e);
        this.save();
    };

    if (typeof(CKEDITOR) != 'undefined') {
        /**
         * Wrap textarea
         */
        $('textarea' + prefix + 'ckeditor').ckeditor(editor_config());
    }

    /**
     * Datetimepicker configuration
     */
    $.fn.datetimepicker.Constructor.Default = $.extend({}, $.fn.datetimepicker.Constructor.Default, {
        icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar',
            up: 'fas fa-arrow-up',
            down: 'fas fa-arrow-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            today: 'far fa-calendar-check',
            clear: 'fas fa-trash',
            close: 'fas fa-times'
        }
    });
    // $(prefix + 'datetime').datetimepicker({
    $('.aplab-admin-field-type-datetimepicker').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss',
        ignoreReadonly: true,
        allowInputToggle: true,
        focusOnShow: true,
        buttons: {
            showToday: true,
            showClear: true,
            showClose: true
        }
        // inline: true,
    });console.log('test');

    /**
     * workaround function to clear autocomplete password
     */
    setTimeout(function () {
        body.find(':password').val('');
    }, 10);

    /**
     * Input image plugin
     */
    body.find('.apl-instance-editor-element-image').each(function (i, o) {
        o = $(o);
        var input = o.find('input').eq(0);
        var previewer = o.find('.preview');
        var btn_upload = o.find('.fa-upload').closest('button');
        btn_upload.click(function () {
            var uploader = AplAdminFileUploader.getInstance();
            uploader.setTitle('Upload images only');
            uploader.setUrl('/admin/xhr/uploadImage/');
            uploader.done = function () {
                AplAdminFileUploader.getInstance().purgeWindow();
                AplAdminImageHistory.getInstance().showWindow();
            };
            uploader.showWindow();
            AplAdminImageHistory.getInstance().beforeDone = function () {
                var items = AplAdminImageHistory.getInstance().getSelectedItems();
                if (!items.length) {
                    return;
                }
                input.val(items[0].path);
                previewer.css({
                    backgroundImage: 'url("' + input.val() + '")'
                });
            };
        });
        var btn_history = o.find('.fa-history').closest('button');
        btn_history.click(function () {
            AplAdminImageHistory.getInstance().showWindow();
            AplAdminImageHistory.getInstance().beforeDone = function () {
                var items = AplAdminImageHistory.getInstance().getSelectedItems();
                if (!items.length) {
                    return;
                }
                input.val(items[0].path);
                previewer.css({
                    backgroundImage: 'url("' + input.val() + '")'
                });
            };
        });
        var btn_favorites = o.find('.fa-star').closest('button');
        btn_favorites.click(function () {
            AplAdminImageHistory.getInstance().showWindow({
                favorites: true
            });
            AplAdminImageHistory.getInstance().beforeDone = function () {
                var items = AplAdminImageHistory.getInstance().getSelectedItems();
                if (!items.length) {
                    return;
                }
                input.val(items[0].path);
                previewer.css({
                    backgroundImage: 'url("' + input.val() + '")'
                });
            };
        });

        previewer.css({
            backgroundImage: 'url("' + input.val() + '")'
        });
        var previewer_timeout;
        input.change(function () {
            if (previewer_timeout) {
                clearTimeout(previewer_timeout);
            }
            previewer_timeout = setTimeout(function () {
                previewer.css({
                    backgroundImage: 'url("' + input.val() + '")'
                });
            }, 400);
        });
    });
}
