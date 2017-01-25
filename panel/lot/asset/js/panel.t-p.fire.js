(function($, win, doc) {

    var form = $.Form, i, j,
        k = form.lot;

    function apply_TP(node, t) {
        var c = TP(node, {
            dateFormat: 'Y/m/d ' + t
        });
        return c;
    }

    form.calendar = {};

    for (i in k) {
        for (j in k[i]) {
            if ($(k[i][j]).hasClass('date')) {
                if (!form.calendar[i]) {
                    form.calendar[i] = {};
                }
                form.calendar[i][j] = apply_TP(k[i][j], k[i][j].value.split(' ').pop());
            }
        }
    }

})(Panel, window, document);