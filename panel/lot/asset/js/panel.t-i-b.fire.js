(function($, win, doc) {

    var forms = $.forms, i, j,
        k = forms.lot;

    function apply_TIB(node) {
        var t = new TIB(node, $.TIB || {});
        t.create();
        t.input.parentNode.className += (' ' + t.input.className.replace(/(\b|\s+)tags-input(\b|\s+)/g, ""));
        return t;
    }

    forms.TIB = {};

    for (i in k) {
        forms.TIB[i] = {};
        for (j in k[i]) {
            if (/(^|\s)(query|TIB)(\s|$)/.test(k[i][j].className)) {
                forms.TIB[i][j] = apply_TIB(k[i][j]);
            }
        }
    }

})(Panel, window, document);