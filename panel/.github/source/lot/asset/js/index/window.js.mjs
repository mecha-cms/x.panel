import {
    B,
    D,
    R,
    W,
    getElement,
    hasClass
} from '@taufik-nurrohman/document';

import {
    offEventDefault,
    onEvent
} from '@taufik-nurrohman/event';

import {
    isFunction
} from '@taufik-nurrohman/is';

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

onEvent('keydown', W, function(e) {
    let t = this,
        key = e.key,
        keyIsAlt = e.altKey,
        keyIsCtrl = e.ctrlKey,
        keyIsShift = e.shiftKey,
        self = e.target,
        target, stop;
    if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
        // Cycle between `lot:bar`, `lot:desk`, `<html>`, and `<window>`
        if ('F6' === key) {
            stop = true;
            if (self === B || self === D || self === R || self === W) {
                target = getElement('.lot\\:bar');
            } else if (hasClass(self, 'lot:bar')) {
                target = getElement('.lot\\:desk');
            } else if (hasClass(self, 'lot:desk')) {
                target = R;
            } else {
                stop = false; // Use default!
            }
            target && isFunction(target.focus) && target.focus();
        } else if ('F10' === key) {
            if (target = (getElement('.lot\\:bar .has\\:menu:first-of-type a[href]:not(.not\\:active)') || getElement('.lot\\:bar'))) {
                isFunction(target.focus) && target.focus();
            }
            stop = true;
        }
    } else if (B !== self && D !== self && R !== self && t !== self) {
        // Skip!
    } else if (keyIsCtrl) {
        if ('f' === key && !keyIsAlt && !keyIsShift) {
            D.forms.get && D.forms.get.q && D.forms.get.q.focus();
            stop = true;
        }
    }
    stop && offEventDefault(e);
});

D.forms.get && D.forms.get.q && onEvent('keydown', D.forms.get.q, function(e) {
    let t = this,
        key = e.key,
        keyIsAlt = e.altKey,
        keyIsCtrl = e.ctrlKey,
        keyIsShift = e.shiftKey, stop;
    if ((keyIsCtrl && 'f' === key || 'Escape' === key) && !keyIsAlt && !keyIsShift) {
        R.focus();
        stop = true;
    }
    stop && offEventDefault(e);
});