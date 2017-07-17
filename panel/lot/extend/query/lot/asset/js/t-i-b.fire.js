(function($, win, doc) {

    var forms = $.forms, i, j,
        k = forms.$;

    function apply_TIB(node) {
        var c = $.TIB || {},
            h = doc.createElement('p');
            h.className = 'h';
        c.alert = function(a, b, $) {
            // `input < span < .f`
            var parent = $.output.parentNode.parentNode;
            h.innerHTML = a;
            parent.parentNode.insertBefore(h, parent.nextElementSibling || parent.nextSibling);
            setTimeout(function() {
                h.parentNode && h.parentNode.removeChild(h);
            }, 5000);
        };
        return new TIB(node, c);
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

})(window.PANEL, window, document);