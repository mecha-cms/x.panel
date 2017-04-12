(function($, win, doc) {

    // $.CP = {};

    var forms = $.forms, i, j,
        k = forms.lot;

    function apply_CP(node) {
        var t = new CP(node);
        node.onclick = function() {
            return false; // disable native color picker
        };
        t.on("change", function(c) {
            var a = this.target,
                b = '#' + c;
            a.style.background = b;
            a.value = b;
        });
        return t;
    }

    forms.CP = {};

    for (i in k) {
        forms.CP[i] = {};
        for (j in k[i]) {
            if (/(^|\s)(color|CP)(\s|$)/.test(k[i][j].className)) {
                forms.CP[i][j] = apply_CP(k[i][j]);
            }
        }
    }

})(Panel, window, document);