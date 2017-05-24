(function($, win, doc) {

    // $.CP = {};

    var forms = $.forms, i, j,
        k = forms.$;

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

    forms.color = {};

    for (i in k) {
        forms.color[i] = {};
        for (j in k[i]) {
            if (/(^|\s)(color|CP)(\s|$)/.test(k[i][j].className)) {
                forms.color[i][j] = apply_CP(k[i][j]);
            }
        }
    }

    forms.CP = forms.color;

})(window.PANEL, window, document);