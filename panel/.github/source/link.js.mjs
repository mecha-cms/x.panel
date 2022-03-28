import {
    D,
    W,
    getChildFirst,
    getElement,
    getElements,
    getNext,
    getParent,
    getPrev,
    hasClass,
    setClass
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

const targets = ':scope>ul>li>a[href]:not(.not\\:active)';

function fireFocus(node) {
    node && isFunction(node.focus) && node.focus();
}

function onChange() {
    let sources = getElements('.lot\\:links[tabindex]');
    sources && toCount(sources) && sources.forEach(source => {
        let links = getElements(targets, source);
        links && toCount(links) && links.forEach(link => {
            onEvent('keydown', link, onKeyDownLink);
        });
        onEvent('keydown', source, onKeyDownLinks);
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
            while (next && (hasClass(next, 'as:separator') || hasClass(next, 'not:active'))) {
                next = getNext(next);
            }
            prev = getPrev(parent);
            while (prev && (hasClass(prev, 'as:separator') || hasClass(prev, 'not:active'))) {
                prev = getPrev(prev);
            }
        }
        if ('ArrowLeft' === key) {
            fireFocus(prev && getChildFirst(prev));
            stop = true;
        } else if ('ArrowRight' === key) {
            fireFocus(next && getChildFirst(next));
            stop = true;
        } else if ('End' === key) {
            if (parent = getParent(t, '.lot\\:links[tabindex]')) {
                any = [].slice.call(getElements(targets, parent));
                fireFocus(any.pop());
            }
            stop = true;
        } else if ('Home' === key) {
            if (parent = getParent(t, '.lot\\:links[tabindex]')) {
                fireFocus(getElement(targets, parent));
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
        keyIsShift = e.shiftKey,
        any, stop;
    if (t !== e.target) {
        return;
    }
    if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
        if ('ArrowRight' === key || 'Home' === key) {
            fireFocus(getElement(targets, t));
            stop = true;
        } else if ('ArrowLeft' === key || 'End' === key) {
            any = [].slice.call(getElements(targets, t));
            fireFocus(any.pop());
            stop = true;
        }
    }
    stop && (offEventDefault(e), offEventPropagation(e));
}

W._.on('change', onChange);