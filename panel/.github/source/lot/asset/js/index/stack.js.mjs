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
    fireEvent,
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
            if (!hasClass(parent, 'has:link')) {
                if (!hasClass(t, 'not:active')) {
                    stacks.forEach(stack => {
                        if (stack !== parent) {
                            letClass(stack, 'is:current');
                            letClass(getElement('a', stack), 'is:current');
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
            let t = getElement('a[target^="stack:"]', stack);
            onEvent('click', t, onClick);
            onEvent('keydown', t, onKeyDownStack);
        });
        let stackCurrent = stacks.find((value, key) => 0 !== key && hasClass(value, 'is:current'));
        if (stackCurrent) {
            input.value = getDatum(stackCurrent, 'value');
        }
        onEvent('keydown', source, onKeyDownStacks);
    });
} onChange();

function onKeyDownStack(e) {
    let t = this,
        key = e.key,
        keyIsAlt = e.altKey,
        keyIsCtrl = e.ctrlKey;
    if (!keyIsAlt && !keyIsCtrl) {
        let current, next, parent, prev;
        if ('ArrowDown' === key || 'PageDown' === key) {
            if (parent = getParent(t)) {
                next = getNext(parent);
                while (next && hasClass(next, 'not:active')) {
                    next = getNext(next);
                }
            }
            if (current = next && getChildFirst(next)) {
                fireEvent('click', current);
                isFunction(current.focus) && current.focus();
            }
            offEventDefault(e);
            offEventPropagation(e);
        } else if ('ArrowLeft' === key) {
            if (hasClass(getParent(t), 'can:toggle') && hasClass(t, 'is:current')) {
                current = t;
            } else {
                if (parent = getParent(t)) {
                    prev = getPrev(parent);
                    while (prev && hasClass(prev, 'not:active')) {
                        prev = getPrev(prev);
                    }
                }
                current = prev && getChildFirst(prev);
            }
            if (current) {
                fireEvent('click', current);
                isFunction(current.focus) && current.focus();
            }
            offEventDefault(e);
            offEventPropagation(e);
        } else if ('ArrowRight' === key) {
            if (hasClass(getParent(t), 'can:toggle') && hasClass(t, 'is:current')) {
                current = t;
            } else {
                if (parent = getParent(t)) {
                    next = getNext(parent);
                    while (next && hasClass(next, 'not:active')) {
                        next = getNext(next);
                    }
                }
                current = next && getChildFirst(next);
            }
            if (current) {
                fireEvent('click', current);
                isFunction(current.focus) && current.focus();
            }
            offEventDefault(e);
            offEventPropagation(e);
        } else if ('ArrowUp' === key || 'PageUp' === key) {
            if (parent = getParent(t)) {
                prev = getPrev(parent);
                while (prev && hasClass(prev, 'not:active')) {
                    prev = getPrev(prev);
                }
            }
            if (current = prev && getChildFirst(prev)) {
                fireEvent('click', current);
                isFunction(current.focus) && current.focus();
            }
            offEventDefault(e);
            offEventPropagation(e);
        } else if (' ' === key || 'Enter' === key) {
            if (hasClass(getParent(t), 'can:toggle')) {
                fireEvent('click', t);
                isFunction(t.focus) && t.focus();
            }
            offEventDefault(e);
            offEventPropagation(e);
        } else if ('Escape' === key) {
            if (isFunction(t.closest) && (parent = t.closest('.lot\\:stacks'))) {
                isFunction(parent.focus) && parent.focus();
            }
            offEventDefault(e);
            offEventPropagation(e);
        }
    }
}

function onKeyDownStacks(e) {
    let t = this,
        key = e.key,
        keyIsAlt = e.altKey,
        keyIsCtrl = e.ctrlKey,
        keyIsShift = e.shiftKey;
    if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
        let current, next, parent, prev;
        if ('ArrowDown' === key || 'ArrowRight' === key || 'PageDown' === key) {
            if (current = getElement('a[target^="stack:"]:not(.not\\:active)', t)) {
                isFunction(current.focus) && current.focus();
            }
            offEventDefault(e);
            offEventPropagation(e);
        } else if ('ArrowUp' === key || 'ArrowLeft' === key || 'PageUp' === key) {
            let links = [].slice.call(getElements('a[target^="stack:"]:not(.not\\:active)', t));
            if (current = links.pop()) {
                isFunction(current.focus) && current.focus();
            }
            offEventDefault(e);
            offEventPropagation(e);
        }
    }
}

W._.on('change', onChange);