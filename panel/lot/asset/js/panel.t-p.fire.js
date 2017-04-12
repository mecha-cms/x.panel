(function($, win, doc) {

    var forms = $.forms, h, i, j,
        k = forms.lot;

    var lot = $.languages.lot,
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
        var c = TP(node, $.extend({
            format: 'Y/m/d' + t,
            languages: languages
        }, $.TP || {}));
        return c;
    }

    forms.date = {};

    for (i in k) {
        for (j in k[i]) {
            if ($(k[i][j]).hasClass('date')) {
                if (!forms.date[i]) {
                    forms.date[i] = {};
                }
                h = k[i][j].value.split(' ').pop();
                forms.date[i][j] = apply_TP(k[i][j], h ? ' ' + h : "");
            }
        }
    }

})(Panel, window, document);