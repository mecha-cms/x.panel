(function() {
    'use strict';
    var D = document;
    var W = window;
    var offEventDefault = function offEventDefault(e) {
        return e && e.preventDefault();
    };
    var onEvent = function onEvent(name, node, then, options) {
        if (options === void 0) {
            options = false;
        }
        node.addEventListener(name, then, options);
    };

    function setWindow(id, {
        title,
        content,
        tasks
    }) { // TODO
    }
    W._.window = setWindow;

    function promisify(type, lot) {
        return new Promise((resolve, reject) => {
            let r = W[type].apply(W, lot);
            return r ? resolve(r) : reject(r);
        });
    } // Prepare for <https://developers.google.com/web/updates/2017/03/dialogs-policy>
    ['alert', 'confirm', 'prompt'].forEach(type => {
        W._.window[type] = (...lot) => promisify(type, lot);
    });
    onEvent('keydown', W, e => {
        let key = e.key,
            keyIsAlt = e.altKey,
            keyIsCtrl = e.ctrlKey;
        if (keyIsAlt && keyIsCtrl) {
            if ('/' === key) {
                D.forms && D.forms.get && D.forms.get.q && D.forms.get.q.focus();
                offEventDefault(e);
            }
        }
    });
})();