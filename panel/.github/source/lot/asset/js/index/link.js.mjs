import {
    D,
    W,
    getChildFirst,
    getElement,
    getElements,
    getNext,
    getParent,
    getPrev,
    hasClass
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

const targets = 'a[href]:not(.not\\:active)';

function onChange() {
    let links = getElements('.lot\\:links');
    links && toCount(links) && links.forEach(link => {
        if (!hasClass(getParent(link), 'lot:tabs')) {
            let linkLinks = getElements(targets, link);
            linkLinks && toCount(linkLinks) && linkLinks.forEach(linkLink => {
                offEvent('keydown', linkLink, onKeyDownLink);
                onEvent('keydown', linkLink, onKeyDownLink);
            });
            offEvent('keydown', link, onKeyDownLinks);
            onEvent('keydown', link, onKeyDownLinks);
        }
    });
} onChange();

function onKeyDownLink(e) {
    if (e.defaultPrevented) {
        return;
    }
    let t = this,
        key = e.key,
        keyIsAlt = e.altKey,
        keyIsCtrl = e.ctrlKey,
        keyIsShift = e.shiftKey,
        any, current, parent, next, prev, stop;
    if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
        if (parent = getParent(t)) {
            next = getNext(parent);
            while (next && (hasClass(next, 'is:separator') || hasClass(next, 'not:active'))) {
                next = getNext(next);
            }
            prev = getPrev(parent);
            while (prev && (hasClass(prev, 'is:separator') || hasClass(prev, 'not:active'))) {
                prev = getPrev(prev);
            }
        }
        if ('ArrowLeft' === key) {
            if (current = prev && getChildFirst(prev)) {
                isFunction(current.focus) && current.focus();
            }
            stop = true;
        } else if ('ArrowRight' === key) {
            if (current = next && getChildFirst(next)) {
                isFunction(current.focus) && current.focus();
            }
            stop = true;
        } else if ('End' === key) {
            if (parent = t.closest('.lot\\:links')) {
                any = [].slice.call(getElements(targets, parent));
                if (current = any.pop()) {
                    isFunction(current.focus) && current.focus();
                }
            }
            stop = true;
        } else if ('Escape' === key) {
            if (parent = t.closest('.lot\\:links')) {
                isFunction(parent.focus) && parent.focus();
            }
        } else if ('Home' === key) {
            if (parent = t.closest('.lot\\:links')) {
                if (current = getElement(targets, parent)) {
                    isFunction(current.focus) && current.focus();
                }
            }
            stop = true;
        }
    }
    stop && (offEventDefault(e), offEventPropagation(e));
}

function onKeyDownLinks(e) {
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
        if ('ArrowRight' === key || 'Home' === key) {
            if (current = getElement(targets, t)) {
                isFunction(current.focus) && current.focus();
            }
            stop = true;
        } else if ('ArrowLeft' === key || 'End' === key) {
            any = [].slice.call(getElements(targets, t));
            if (current = any.pop()) {
                isFunction(current.focus) && current.focus();
            }
            stop = true;
        }
    }
    stop && (offEventDefault(e), offEventPropagation(e));
}

W._.on('change', onChange);