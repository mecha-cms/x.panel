import {
    B,
    D,
    R,
    W,
    getElement,
    getFormElement,
    getParent,
    hasClass
} from '@taufik-nurrohman/document';

import {
    fireEvent,
    offEventDefault,
    onEvent
} from '@taufik-nurrohman/event';

import {
    hook
} from '@taufik-nurrohman/hook';

import {
    isFunction
} from '@taufik-nurrohman/is';

import K from '@taufik-nurrohman/key';

let map = new K(W);

onEvent('blur', W, e => map.pull());

onEvent('keydown', W, e => {
    map.push(e.key);
    let command = map.test();
    if (command) {
        let value = map.fire(command);
        if (false === value) {
            offEventDefault(e);
        } else if (null === value) {
            console.error('Unknown command:', command);
        }
    }
});

onEvent('keyup', W, e => map.pull(e.key));

const _ = {
    commands: map.commands,
    keys: map.keys
};

const {fire, hooks, off, on} = hook(_);

W.K = K;
W._ = _;

onEvent('beforeload', D, () => fire('let'));
onEvent('load', D, () => fire('get'));
onEvent('DOMContentLoaded', D, () => fire('set'));

const mainSearchForm = getFormElement('get');
const mainSearchFormInput = mainSearchForm && mainSearchForm.q;

onEvent('keydown', W, function(e) {
    // Since removing events is not possible here, checking if another event has been added is the only way
    // to prevent the declaration below from executing if previous events have blocked it.
    if (e.defaultPrevented) {
        return;
    }
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
            if (target = (getElement('.lot\\:bar a[href]:not(.not\\:active)') || getElement('.lot\\:bar'))) {
                if (hasClass(getParent(target), 'has:menu')) {
                    fireEvent('click', target);
                }
                isFunction(target.focus) && target.focus();
            }
            stop = true;
        }
    } else if (B !== self && D !== self && R !== self && t !== self) {
        // Skip!
    } else if (keyIsCtrl) {
        if ('?' === key && !keyIsAlt) {
            console.info('TODO: Go to the about page.');
            stop = true;
        } else if ('f' === key && !keyIsAlt) {
            mainSearchFormInput && mainSearchFormInput.focus();
            stop = true;
        }
    }
    stop && offEventDefault(e);
});

mainSearchFormInput && onEvent('keydown', mainSearchFormInput, function(e) {
    if (e.defaultPrevented) {
        return;
    }
    let t = this,
        key = e.key,
        keyIsAlt = e.altKey,
        keyIsCtrl = e.ctrlKey,
        keyIsShift = e.shiftKey, stop;
    if ((keyIsCtrl && 'f' === key || 'Escape' === key) && !keyIsAlt && !keyIsShift) {
        R.focus(); // Focus back to the `<html>`!
        stop = true;
    }
    stop && offEventDefault(e);
});