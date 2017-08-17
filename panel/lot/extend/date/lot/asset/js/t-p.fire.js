(function($, win, doc) {

    var form = $.__form__, h, i, j,
        k = form.$;

    var lot = win.$language,
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
        }, $config.TP || {}));
        return c;
    }

    form.date = {};

    for (i in k) {
        form.date[i] = {};
        for (j in k[i]) {
            if (/(^|\s)(date|TP)(\s|$)/.test(k[i][j].className)) {
                h = k[i][j].value.split(' ').pop();
                form.date[i][j] = apply_TP(k[i][j], h ? ' ' + h : "");
            }
        }
    }

    form.TP = form.date;

})(window.PANEL, window, document);