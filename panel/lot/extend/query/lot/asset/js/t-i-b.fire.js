(function($, win, doc) {

    var form = $.__form__, i, j,
        k = form.$;

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

    form.query = {};

    for (i in k) {
        form.query[i] = {};
        for (j in k[i]) {
            if (/(^|\s)(query|TIB)(\s|$)/.test(k[i][j].className)) {
                form.query[i][j] = apply_TIB(k[i][j]);
            }
        }
    }

    form.TIB = form.query;

})(window.PANEL, window, document);