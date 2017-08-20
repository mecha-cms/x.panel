(function($, win, doc) {

    var form = $.__form__,
        i, j, k = form.$;

    function apply_CodeMirror(node) {
        var size = [$(node).outerWidth(), $(node).outerHeight()],
            editor = CodeMirror.fromTextArea(node, typeof $config.CM === "object" ? $config.CM : {
                lineNumbers: true,
                lineWrapping: true
            });
        var type = $(node).data('type'),
            def = 'application/x-httpd-php',
            aliases = {
                'html': def,
                'markdown': {
                    'name': 'text/x-markdown',
                    'fencedCodeBlocks': true
                },
                'xml': def
            };
        editor.on("blur", function() {
            editor.save(); // update `<textarea>` value on every “blur” event
        });
        editor.addKeyMap({
            'Ctrl-J': 'toMatchingTag',
            'Ctrl-Space': function(cm) {
                return cm.showHint && cm.showHint({hint: CodeMirror.hint.anyword});
            },
            'F11': function(cm) {
                cm.setOption('fullScreen', !cm.getOption('fullScreen'));
            }
        });
        if (type) {
            type = type.toLowerCase();
            editor.setOption('mode', aliases[type] || type);
            if (type === 'markdown') {
                editor.addKeyMap({
                    'Enter': 'newlineAndIndentContinueMarkdownList'
                });
            }
        }
        editor.setSize(size[0], size[1]);
        $(win).on("resize.panel", function() {
            $(editor.display.wrapper).width(0).width($(node).parent().width());
        });
        return editor;
    }

    form.editor = {};

    for (i in k) {
        form.editor[i] = {};
        for (j in k[i]) {
            if (/(^|\s)(editor|CodeMirror|CM)(\s|$)/.test(k[i][j].className)) {
                form.editor[i][j] = apply_CodeMirror(k[i][j]);
            }
        }
    }

    form.CM = form.editor;

})(window.PANEL, window, document);