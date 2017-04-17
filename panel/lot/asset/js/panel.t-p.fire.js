(function($, win, doc) {

    var forms = $.forms, h, i, j,
        k = forms.$;

    var lot = $.languages.$,
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
        forms.date[i] = {};
        for (j in k[i]) {
            if (/(^|\s)(date|TP)(\s|$)/.test(k[i][j].className)) {
                h = k[i][j].value.split(' ').pop();
                forms.date[i][j] = apply_TP(k[i][j], h ? ' ' + h : "");
            }
        }
    }

    forms.TP = forms.date;

})(Panel, window, document);