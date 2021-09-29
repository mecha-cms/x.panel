import {
    D,
    W
} from '@taufik-nurrohman/document';

import {
    offEventDefault,
    onEvent
} from '@taufik-nurrohman/event';

function setWindow(id, {title, content, tasks}) {
    // TODO
}

W._.window = setWindow;

function promisify(type, lot) {
    return new Promise((resolve, reject) => {
        let r = W[type].apply(W, lot);
        return r ? resolve(r) : reject(r);
    });
}

// Prepare for <https://developers.google.com/web/updates/2017/03/dialogs-policy>
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