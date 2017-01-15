(function($, win, doc) {

    var form = $.Form,
        i, j, k = form.lot;

    function apply_CodeMirror(node) {
        var size = $(node).size(),
            editor = CodeMirror.fromTextArea(node, {
                lineNumbers: true,
                lineWrapping: true,
                mode: 'application/x-httpd-php',
                addModeClass: true,
                matchBrackets: true,
                matchTags: {
                    bothTags: true
                },
                extraKeys: {
                    'Ctrl-J': 'toMatchingTag',
                    'F11': function (cm) {
                        cm.setOption('fullScreen', !cm.getOption('fullScreen'));
                    }
                },
                autoCloseTags: {
                    whenClosing: true,
                    whenOpening: true,
                    dontCloseTags: ["area", "base", "br", "col", "command", "embed", "hr", "img", "input", "keygen", "link", "meta", "param",
                       "source", "track", "wbr"],
                    indentTags: ["blockquote", "body", "div", "dl", "fieldset", "form", "frameset", "h1", "h2", "h3", "h4",
                    "h5", "h6", "head", "html", "object", "ol", "select", "table", "tbody", "tfoot", "thead", "tr", "ul"]
                },
                autoCloseBrackets: true,
                styleActiveLine: false
            });
        var display = node.style.display;
        editor.setSize(size.x, size.y);
        $.events.set("resize", win, function() {
            node.style.display = "";
            editor.display.wrapper.style.width = 'auto';
            size = $(node).size();
            editor.setSize(size.x, size.y);
            node.style.display = display;
        });
        return editor;
    }

    form.editor = {};

    for (i in k) {
        for (j in k[i]) {
            if ($(k[i][j]).classes.get('code')) {
                if (!form.editor[i]) {
                    form.editor[i] = {};
                }
                form.editor[i][j] = apply_CodeMirror(k[i][j]);
            }
        }
    }

    function apply_TIB(node) {
        var t = new TIB(node, {
            max: 12
        });
        t.create();
        t.input.parentNode.className += (' ' + t.input.className.replace(/(\b|\s+)tags-input(\b|\s+)/g, ""));
        return t;
    }

    form.query = {};

    for (i in k) {
        for (j in k[i]) {
            if ($(k[i][j]).classes.get('query')) {
                if (!form.query[i]) {
                    form.query[i] = {};
                }
                form.query[i][j] = apply_TIB(k[i][j]);
            }
        }
    }
    
})(Panel, window, document);