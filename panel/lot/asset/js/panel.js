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

    _.ASSET_CSS = {
        'css/panel/field/query': {}
    };

    _.ASSET_JS = {
        'js/panel/alert': {},
        'js/panel/field/query': {},
        'js/panel/field/source': {},
        'js/panel/menu': {},
        'js/panel/tab': {}
    };

    var src = doc.currentScript.src,
        a = src.split('/'), i,
        // `../`
        end = a.pop().split('?')[1] || '0';

    // `../`
    a.pop();

    src = a.join('/');

    // Load CSS file(s) immediately
    for (i in _.ASSET_CSS) {
        if (!_.ASSET_CSS[i] || _.ASSET_CSS[i].once) {
            continue;
        }
        var link = doc.createElement('link');
        link.href = src + '/' + i + '.min.css?' + end;
        link.rel = 'stylesheet';
        doc.head.appendChild(link);
        _.ASSET_CSS[i].once = true;
    }

    // Load JS file(s) on document ready
    _.on('load', function() {
        for (i in _.ASSET_JS) {
            if (!_.ASSET_JS[i] || _.ASSET_JS[i].once) {
                continue;
            }
            var script = doc.createElement('script');
            script.src = src + '/' + i + '.min.js?' + end;
            doc.head.appendChild(script);
            _.ASSET_JS[i].once = true;
        }
    });

    win.addEventListener('DOMContentLoaded', function() {
        _.fire('load');
    });

})(window, document, window._ = window._ || {});