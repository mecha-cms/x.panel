(function($, win, doc) {

    var forms = $.forms,
        i, j, k = forms.lot;

    function apply_CodeMirror(node) {
        var size = [$(node).outerWidth(), $(node).outerHeight()],
            editor = CodeMirror.fromTextArea(node, {
                lineNumbers: true,
                lineWrapping: true,
                mode: 'application/x-httpd-php',
                addModeClass: true,
                matchBrackets: true,
                matchTags: {
                    bothTags: true
                },
                autoCloseTags: {
                    whenClosing: true,
                    whenOpening: true,
                    dontCloseTags: ["area", "base", "br", "col", "command", "embed", "hr", "img", "input", "keygen", "link", "meta", "param",
                       "source", "track", "wbr"],
                    indentTags: ["blockquote", "body", "div", "dl", "fieldset", "form", "frameset", "h1", "h2", "h3", "h4",
                    "h5", "h6", "head", "html", "object", "ol", "select", "table", "tbody", "tfoot", "thead", "tr", "ul"]
                },
                autoCloseBrackets: true
            });
        var type = $(node).data('type'),
            aliases = {
                'html': 'application/x-httpd-php',
                'xml': 'application/x-httpd-php',
                'markdown': {
                    'name': 'text/x-markdown',
                    'fencedCodeBlocks': true
                }
            };
        editor.addKeyMap({
            'Ctrl-J': 'toMatchingTag',
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
        $(win).on("resize", function() {
            $(editor.display.wrapper).width(0).width($(node).parent().width());
        });
        return editor;
    }

    forms.editor = {};

    for (i in k) {
        for (j in k[i]) {
            if (/(^|\s)(editor|CodeMirror|CM)(\s|$)/.test(k[i][j].className)) {
                forms.editor[i] = {};
                forms.editor[i][j] = apply_CodeMirror(k[i][j]);
            }
        }
    }

    forms.CM = forms.editor;

})(Panel, window, document);