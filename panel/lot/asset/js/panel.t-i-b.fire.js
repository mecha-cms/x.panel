(function($, win, doc) {

    var forms = $.forms, i, j,
        k = forms.$;

    function apply_TIB(node) {
        var k = node.className,
            t = new TIB(node, $.TIB || {});
        t.view.className += (' ' + k);
        return t;
    }

    forms.query = {};

    for (i in k) {
        forms.query[i] = {};
        for (j in k[i]) {
            if (/(^|\s)(query|TIB)(\s|$)/.test(k[i][j].className)) {
                forms.query[i][j] = apply_TIB(k[i][j]);
            }
        }
    }

    forms.TIB = forms.query;

})(Panel, window, document);