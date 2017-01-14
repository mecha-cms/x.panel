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
                    },
                    'Esc': function (cm) {
                        if (cm.getOption('fullScreen')) {
                            cm.setOption('fullScreen', false);
                        }
                    },
                    'Ctrl-B': function(cm) {
                        var s = cm.getSelection(),
                            t = s.slice(0, 8) === '<strong>' && s.slice(-9) === '</strong>';
                        cm.replaceSelection(t ? s.slice(8, -9) : '<strong>' + s + '</strong>', 'around');
                    },
                    'Ctrl-I': function(cm) {
                        var s = cm.getSelection(),
                            t = s.slice(0, 4) === '<em>' && s.slice(-5) === '</em>';
                        cm.replaceSelection(t ? s.slice(4, -5) : '<em>' + s + '</em>', 'around');
                    },
                    'Ctrl-K': function(cm) {
                        var s = cm.getSelection(),
                            t = s.slice(0, 6) === '<code>' && s.slice(-7) === '</code>';
                        cm.replaceSelection(t ? s.slice(6, -7).replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&amp;/g, '&') : '<code>' + s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</code>', 'around');
                    }
                },
                autoCloseTags: true,
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
        return (new TIB(node, {
            max: 12
        })).create();
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