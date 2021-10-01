import {
    W,
    getElement,
    getElements,
    getNext,
    getParent,
    getPrev,
    hasClass
} from '@taufik-nurrohman/document';

import {
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
    let sources = getElements('.lot\\:pages');
    sources && toCount(sources) && sources.forEach(source => {
        let files = getElements('.lot\\:page:not(.not\\:active)', source);
        files.forEach(file => {
            if (source === getParent(file)) {
                onEvent('keydown', file, onKeyDownPage);
            }
        });
        onEvent('keydown', source, onKeyDownPages);
    });
} onChange();

function onKeyDownPage(e) {
    let t = this,
        key = e.key,
        any, current, parent, next, prev, stop;
    if (t !== e.target) {
        return;
    }
    next = getNext(t);
    while (next && hasClass(next, 'not:active')) {
        next = getNext(next);
    }
    prev = getPrev(t);
    while (prev && hasClass(prev, 'not:active')) {
        prev = getPrev(prev);
    }
    if ('ArrowDown' === key) {
        if (next && isFunction(next.focus)) {
            next.focus();
        }
        stop = true;
    } else if ('ArrowUp' === key) {
        if (prev && isFunction(prev.focus)) {
            prev.focus();
        }
        stop = true;
    } else if ('End' === key) {
        any = [].slice.call(getElements('.lot\\:page:not(.not\\:active)', getParent(t)));
        if (current = any.pop()) {
            isFunction(current.focus) && current.focus();
        }
        stop = true;
    } else if ('Escape' === key) {
        if (current = getParent(t)) {
            isFunction(current.focus) && current.focus();
        }
        stop = true;
    } else if ('Home' === key) {
        if (current = getElement('.lot\\:page:not(.not\\:active)', getParent(t))) {
            isFunction(current.focus) && current.focus();
        }
        stop = true;
    }
    stop && (offEventDefault(e), offEventPropagation(e));
}

function onKeyDownPages(e) {
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
        if ('ArrowDown' === key || 'Home' === key) {
            if (current = getElement('.lot\\:page:not(.not\\:active)', t)) {
                isFunction(current.focus) && current.focus();
            }
            stop = true;
        } else if ('ArrowUp' === key || 'End' === key) {
            any = [].slice.call(getElements('.lot\\:page:not(.not\\:active)', t));
            if (current = any.pop()) {
                isFunction(current.focus) && current.focus();
            }
            stop = true;
        }
    }
    stop && (offEventDefault(e), offEventPropagation(e));
}

W._.on('change', onChange);