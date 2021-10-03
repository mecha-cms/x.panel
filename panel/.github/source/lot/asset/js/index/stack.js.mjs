import {
    W,
    getChildFirst,
    getChildren,
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
    setElement,
    toggleClass
} from '@taufik-nurrohman/document';

import {
    fireEvent,
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

const targets = 'a[target^="stack:"]:not(.not\\:active)';

function onChange() {
    let sources = getElements('.lot\\:stacks[tabindex]');
    sources && toCount(sources) && sources.forEach(source => {
        let stacks = [].slice.call(getChildren(source)),
            input = setElement('input'), name, value;
        input.type = 'hidden';
        input.name = name = getDatum(source, 'name');
        setChildLast(source, input);
        function onClick(e) {
            let t = this,
                parent = getParent(getParent(t));
            if (!hasClass(parent, 'has:link')) {
                stacks.forEach(stack => {
                    if (stack !== parent) {
                        letClass(stack, 'is:current');
                        letClass(getElement('a[target^="stack:"]', stack), 'is:current');
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
                W._.fire.apply(parent, ['change.stack', [value, name]]);
            }
            offEventDefault(e);
        }
        stacks.forEach(stack => {
            let target = getElement(targets, stack);
            onEvent('click', target, onClick);
            onEvent('keydown', target, onKeyDownStack);
        });
        let stackCurrent = stacks.find((value, key) => 0 !== key && hasClass(value, 'is:current'));
        if (stackCurrent) {
            input.value = getDatum(stackCurrent, 'value');
        }
        onEvent('keydown', source, onKeyDownStacks);
    });
} onChange();

function onKeyDownStack(e) {
    if (e.defaultPrevented) {
        return;
    }
    let t = this,
        key = e.key,
        keyIsAlt = e.altKey,
        keyIsCtrl = e.ctrlKey, stop;
    if (!keyIsAlt && !keyIsCtrl) {
        let any, current, next, parent, prev;
        if ('ArrowDown' === key || 'ArrowRight' === key || 'PageDown' === key) {
            if ('ArrowRight' === key) {
                // TODO
            }
            if (parent = getParent(getParent(t))) {
                next = getNext(parent);
                while (next && hasClass(next, 'not:active')) {
                    next = getNext(next);
                }
            }
            if (current = next && getChildFirst(next)) {
                if ('ArrowRight' !== key || !hasClass(getParent(current), 'can:toggle')) {
                    fireEvent('click', getChildFirst(current));
                }
                current = getChildFirst(current);
                isFunction(current.focus) && current.focus();
            }
            stop = true;
        } else if ('ArrowUp' === key || 'ArrowLeft' === key || 'PageUp' === key) {
            if ('ArrowLeft' === key) {
                // TODO
            }
            if (parent = getParent(getParent(t))) {
                prev = getPrev(parent);
                while (prev && hasClass(prev, 'not:active')) {
                    prev = getPrev(prev);
                }
            }
            if (current = prev && getChildFirst(prev)) {
                if ('ArrowLeft' !== key || !hasClass(getParent(current), 'can:toggle')) {
                    fireEvent('click', getChildFirst(current));
                }
                current = getChildFirst(current);
                isFunction(current.focus) && current.focus();
            }
            stop = true;
        } else if (' ' === key || 'Enter' === key) {
            if (hasClass(getParent(getParent(t)), 'can:toggle')) {
                fireEvent('click', t);
                isFunction(t.focus) && t.focus();
            }
            stop = true;
        } else if ('Escape' === key) {
            if (parent = t.closest('.lot\\:stacks[tabindex]')) {
                isFunction(parent.focus) && parent.focus();
            }
            stop = true;
        } else if ('End' === key) {
            if (parent = t.closest('.lot\\:stacks[tabindex]')) {
                any = [].slice.call(getElements(targets, parent));
                if (current = any.pop()) {
                    fireEvent('click', current);
                    isFunction(current.focus) && current.focus();
                }
            }
            stop = true;
        } else if ('Home' === key) {
            if (parent = t.closest('.lot\\:stacks[tabindex]')) {
                if (current = getElement(targets, parent)) {
                    fireEvent('click', current);
                    isFunction(current.focus) && current.focus();
                }
            }
            stop = true;
        }
    }
    stop && (offEventDefault(e), offEventPropagation(e));
}

function onKeyDownStacks(e) {
    if (e.defaultPrevented) {
        return;
    }
    let t = this,
        key = e.key,
        keyIsAlt = e.altKey,
        keyIsCtrl = e.ctrlKey,
        keyIsShift = e.shiftKey, stop;
    if (t !== e.target) {
        return;
    }
    if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
        let any, current, next, parent, prev;
        if ('ArrowDown' === key || 'ArrowRight' === key || 'Home' === key || 'PageDown' === key) {
            if (current = getElement(targets, t)) {
                fireEvent('click', current);
                isFunction(current.focus) && current.focus();
            }
            stop = true;
        } else if ('ArrowUp' === key || 'ArrowLeft' === key || 'End' === key || 'PageUp' === key) {
            any = [].slice.call(getElements(targets, t));
            if (current = any.pop()) {
                fireEvent('click', current);
                isFunction(current.focus) && current.focus();
            }
            stop = true;
        }
    }
    stop && (offEventDefault(e), offEventPropagation(e));
}

W._.on('change', onChange);