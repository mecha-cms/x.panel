import {
    W,
    getChildFirst,
    getChildren,
    getClasses,
    getDatum,
    getElement,
    getElements,
    getNext,
    getParent,
    getPrev,
    hasClass,
    letClass,
    setChildLast,
    setClass,
    setClasses,
    setElement,
    toggleClass
} from '@taufik-nurrohman/document';

import {
    offEvent,
    offEventDefault,
    offEventPropagation,
    onEvent
} from '@taufik-nurrohman/event';

import {
    isFunction
} from '@taufik-nurrohman/is';

import {
    toCount
} from '@taufik-nurrohman/to';

function onChange() {
    let sources = getElements('.lot\\:stacks');
    sources && toCount(sources) && sources.forEach(source => {
        let stacks = [].slice.call(getChildren(source)),
            input = setElement('input'), name, value;
        input.type = 'hidden';
        input.name = name = getDatum(source, 'name');
        setChildLast(source, input);
        function onClick(e) {
            let t = this,
                parent = getParent(t);
            if (!hasClass(t, 'has:link')) {
                if (!hasClass(t, 'not:active')) {
                    stacks.forEach(stack => {
                        if (stack !== parent) {
                            letClass(stack, 'is:current');
                            letClass(getElement('.title', stack), 'is:current');
                        }
                    });
                    if (hasClass(parent, 'can:toggle')) {
                        toggleClass(t, 'is:current');
                        toggleClass(parent, 'is:current');
                        input.value = value = hasClass(t, 'is:current') ? getDatum(parent, 'value') : null;
                    } else {
                        setClass(t, 'is:current');
                        setClass(parent, 'is:current');
                        input.value = value = getDatum(parent, 'value');
                    }
                    W._.fire('change.stack', [name, value]);
                }
                offEventDefault(e);
            }
        }
        stacks.forEach(stack => {
            onEvent('click', getElement('.title', stack), onClick);
        });
        let stackCurrent = stacks.find((value, key) => 0 !== key && hasClass(value, 'is:current'));
        if (stackCurrent) {
            input.value = getDatum(stackCurrent, 'value');
        }
        onEvent('keydown', source, onKeyDown);
    });
} onChange();

function onKeyDown(e) {
    let t = this,
        key = e.key,
        keyIsAlt = e.altKey,
        keyIsCtrl = e.ctrlKey;
    if (keyIsAlt && keyIsCtrl) {
        let current, next, parent, prev;
        if ('PageDown' === key) {
            current = getElement('.title.is\\:current', t);
            parent = current && getParent(current);
            if (parent) {
                while (next = getNext(parent)) {
                    if (!hasClass(next, 'not:active')) {
                        break;
                    }
                }
            }
            current = next && getElement('.title', next);
            if (current) {
                isFunction(current.focus) && current.focus();
                isFunction(current.click) && current.click();
            }
            offEventDefault(e);
            offEventPropagation(e);
        } else if ('PageUp' === key) {
            current = getElement('.title.is\\:current', t);
            parent = current && getParent(current);
            if (parent) {
                while (prev = getPrev(parent)) {
                    if (!hasClass(prev, 'not:active')) {
                        break;
                    }
                }
            }
            current = prev && getElement('.title', prev);
            if (current) {
                isFunction(current.focus) && current.focus();
                isFunction(current.click) && current.click();
            }
            offEventDefault(e);
            offEventPropagation(e);
        }
    }
}

W._.on('change', onChange);