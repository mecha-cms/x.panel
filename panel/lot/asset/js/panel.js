(function(win, doc, _) {

    let hooks = {};

    function isSet(x) {
        return 'undefined' !== typeof x;
    }

    _.on = function(name, fn) {
        if (!isSet(hooks[name])) {
            hooks[name] = [];
        }
        if (isSet(fn)) {
            hooks[name].push(fn);
        }
        return _;
    };

    _.off = function(name, fn) {
        if (!isSet(name)) {
            return (hooks = {}), _;
        }
        if (isSet(hooks[name])) {
            if (isSet(fn)) {
                for (let i = 0, j = hooks[name].length; i < j; ++i) {
                    if (fn === hooks[name][i]) {
                        hooks[name].splice(i, 1);
                    }
                }
                // Clean-up empty hook(s)
                if (0 === j) {
                    delete hooks[name];
                }
            } else {
                delete hooks[name];
            }
        }
        return _;
    };

    _.fire = function(name, lot) {
        if (!isSet(hooks[name])) {
            return _;
        }
        for (let i = 0, j = hooks[name].length; i < j; ++i) {
            hooks[name][i].apply(_, lot);
        }
        return _;
    };

    _.hooks = hooks;

    doc.addEventListener('load', function() {
        _.fire('get');
    });

    doc.addEventListener('beforeunload', function() {
        _.fire('let');
    });

    doc.addEventListener('DOMContentLoaded', function() {
        _.fire('set');
    });

    _.on('let', function() {
        let title = doc.querySelector('title');
        var is = title.getAttribute('data-is') || 'pull',
            titleIs = title.getAttribute('data-is-' + is);
        titleIs && (doc.title = titleIs);
    });

    doc.documentElement.classList.add('can:fetch');

})(window, document, window._ = window._ || {});
