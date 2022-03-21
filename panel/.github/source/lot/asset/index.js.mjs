import {
    B,
    D,
    R,
    W,
    getElement,
    getFormElement,
    getParent,
    hasClass,
    setChildLast,
    setElement,
    setHTML
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

let _alert = setElement('dialog'),
    _confirm = setElement('dialog'),
    _prompt = setElement('dialog');

setHTML(_alert, '<form method="dialog"><p>Test alert dialog.</p><p><button type="submit">OK</button></p></form>');
setHTML(_confirm, '<form method="dialog"><p>Test confirm dialog.</p><p><button type="submit">OK</button> <button type="submit">Cancel</button></p></form>');
setHTML(_prompt, '<form method="dialog"><p>Test prompt dialog.</p><p><input type="text" class="has:width"></p><p><button type="submit">OK</button> <button type="submit">Cancel</button></p></form>');

setChildLast(B, _alert);
setChildLast(B, _confirm);
setChildLast(B, _prompt);

const _ = {
    alert: _alert,
    commands: map.commands,
    confirm: _confirm,
    keys: map.keys,
    prompt: _prompt
};

const {fire, hooks, off, on} = hook(_);

W.K = K;
W._ = _;

onEvent('beforeload', D, () => fire('let'));
onEvent('load', D, () => fire('get'));
onEvent('DOMContentLoaded', D, () => fire('set'));

onEvent('keydown', W, function(e) {
    if (e.defaultPrevented) {
        return;
    }
    let t = this,
        target = e.target,
        key = e.key,
        keyIsAlt = e.altKey,
        keyIsCtrl = e.ctrlKey,
        keyIsShift = e.shiftKey,
        firstBarFocusable = getElement('.lot\\:bar a:any-link'),
        mainSearchForm = getFormElement('get'),
        mainSearchFormInput = mainSearchForm && mainSearchForm.q,
        parent, stop;
    if (mainSearchFormInput && 'F3' === key && !keyIsAlt && !keyIsCtrl && !keyIsShift) {
        mainSearchFormInput.focus();
        stop = true;
    } else if (firstBarFocusable && 'F10' === key && !keyIsAlt && !keyIsCtrl && !keyIsShift) {
        firstBarFocusable.focus();
        if (parent = getParent(firstBarFocusable)) {
            if (hasClass(parent, 'has:menu')) {
                firstBarFocusable.click();
            }
        }
        stop = true;
    } else if (B !== target && R !== target && W !== target) {
        if ('Escape' === key && (parent = getParent(getParent(target), '[tabindex]:not(.not\\:active)'))) {
            parent.focus();
            stop = true;
        }
    }
    stop && offEventDefault(e);
});