(function($, win, doc) {

    var form = $.Form,
        k = form.lot;

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