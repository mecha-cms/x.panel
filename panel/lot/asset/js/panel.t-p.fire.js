(function($, win, doc) {

    var form = $.Form, h, i, j,
        k = form.lot;

    var lot = $.Language.lot,
        languages = {
        days: {
            short: lot.days_short.map(function(v) {
                return v[0];
            }),
            long: lot.days_long
        },
        months: {
            short: lot.months_short,
            long: lot.months_long
        }
    }

    function apply_TP(node, t) {
        var c = TP(node, {
            format: 'Y/m/d' + t,
            languages: languages
        });
        return c;
    }

    form.date = {};

    for (i in k) {
        for (j in k[i]) {
            if ($(k[i][j]).hasClass('date')) {
                if (!form.date[i]) {
                    form.date[i] = {};
                }
                h = k[i][j].value.split(' ').pop();
                form.date[i][j] = apply_TP(k[i][j], h ? ' ' + h : "");
            }
        }
    }

})(Panel, window, document);