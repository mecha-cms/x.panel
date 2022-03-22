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

import {
    toValue
} from '@taufik-nurrohman/to';

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

let _dialog = setElement('dialog');

onEvent('submit', _dialog, e => {
    console.log(_dialog.returnValue);
});

setChildLast(B, _dialog);

function dialog(content) {
    setHTML(_dialog, '<form method="dialog">' + content + '</form>');
    _dialog.showModal();
    let target = getElement('[autofocus]', _dialog);
    if (target) {
        isFunction(target.focus) && target.focus();
        isFunction(target.select) && target.select(); // `<input>`
    }
}

dialog.alert = function(description) {
    return dialog('<p>' + description + '</p><p role="group"><button autofocus name="v" type="submit" value="1">OK</button></p>');
};

dialog.confirm = function(description) {
    return dialog('<p>' + description + '</p><p role="group"><button name="v" type="submit" value="1">OK</button> <button autofocus name="v" type="submit" value="0">Cancel</button></p>');
};

dialog.prompt = function(key, value) {
    value = value.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    return dialog('<p>' + key + '</p><p><input autofocus type="text" value="' + value + '"></p><p role="group"><button name="v" type="submit" value="1">OK</button> <button name="v" type="submit" value="0">Cancel</button></p>');
};

const _ = {
    commands: map.commands,
    dialog,
    keys: map.keys
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