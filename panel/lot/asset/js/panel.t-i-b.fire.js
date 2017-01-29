(function($, win, doc) {

    var forms = $.forms, i, j,
        k = forms.lot;

    function apply_TIB(node) {
        var t = new TIB(node, {
            max: 12,
            text: ["", ""],
            alert: false
        });
        t.create();
        t.input.parentNode.className += (' ' + t.input.className.replace(/(\b|\s+)tags-input(\b|\s+)/g, ""));
        return t;
    }

    forms.query = {};

    for (i in k) {
        for (j in k[i]) {
            if ($(k[i][j]).hasClass('query')) {
                if (!forms.query[i]) {
                    forms.query[i] = {};
                }
                forms.query[i][j] = apply_TIB(k[i][j]);
            }
        }
    }

})(Panel, window, document);