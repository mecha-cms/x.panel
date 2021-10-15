(function() {
    'use strict';
    var W = window;

    function setDialog(id, {
        title,
        content,
        tasks
    }) {
        // TODO
        return {};
    }
    W._.dialog = setDialog;

    function promisify(type, lot) {
        return new Promise((resolve, reject) => {
            let r = W[type].apply(W, lot);
            return r ? resolve(r) : reject(r);
        });
    } // Prepare for <https://developers.google.com/web/updates/2017/03/dialogs-policy>
    ['alert', 'confirm', 'prompt'].forEach(type => {
        W._.dialog[type] = (...lot) => promisify(type, lot);
    });
})();