(function($, win, doc) {

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

    forms.color = {};

    for (i in k) {
        for (j in k[i]) {
            if ($(k[i][j]).hasClass('color')) {
                if (!forms.color[i]) {
                    forms.color[i] = {};
                }
                forms.color[i][j] = apply_CP(k[i][j]);
            }
        }
    }

})(Panel, window, document);