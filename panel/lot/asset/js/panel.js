(function(win, doc, _) {

        function count(x) {
            return Object.keys(x).length;
        }

        function isSet(x) {
            return 'undefined' !== typeof x;
        }

        var hooks = {};

        _.on = function(name, fn, id) {
            if (!isSet(hooks[name])) {
                hooks[name] = {};
            }
            if (!isSet(id)) {
                id = count(hooks[name]);
            }
            return hooks[name][id] = fn, _;
        };

        _.off = function(name, id) {
            if (!isSet(name)) {
                return hooks = {}, _;
            }
            if (!isSet(id)) {
                return hooks[name] = {}, _;
            }
            return delete hooks[name][id], _;
        };

        _.fire = function(name, lot, id) {
            if (!isSet(hooks[name])) {
                return _;
            }
            if (!isSet(id)) {
                for (var i in hooks[name]) {
                    hooks[name][i].apply(_, lot);
                }
            } else {
                if (isSet(hooks[name][id])) {
                    hooks[name][id].apply(_, lot);
                }
            }
            return _;
        };

    var src = doc.currentScript.src,
        a = src.split('/'), i,
        // `../`
        end = a.pop().split('?')[1] || '0';

    // `../`
    a.pop();

    src = a.join('/');

    _.folder = src;
    _.hooks = hooks;

    _.on('pop', function() {
        // ...
    });

    doc.addEventListener('DOMContentLoaded', function() {
        _.fire('set');
    });

})(window, document, window._ = window._ || {});
