import {
    fireFocus,
    onEventOnly
} from '../../_.mjs';

import {
    W,
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
    offEventPropagation
} from '@taufik-nurrohman/event';

import {
    toCount
} from '@taufik-nurrohman/to';

const targets = ':scope>.lot\\:page[tabindex]:not([tabindex="-1"]):not(.not\\:active)';

function onChange(init) {
    let sources = getElements('.lot\\:pages[tabindex]');
    sources && toCount(sources) && sources.forEach(source => {
        let pages = getElements(targets, source);
        pages.forEach(page => {
            onEventOnly('keydown', page, onKeyDownPage);
        });
        onEventOnly('keydown', source, onKeyDownPages);
    });
    1 === init && W._.on('change', onChange);
}

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
        fireFocus(next);
        stop = true;
    } else if ('ArrowUp' === key) {
        fireFocus(prev);
        stop = true;
    } else if ('End' === key) {
        any = [].slice.call(getElements(targets, getParent(t)));
        fireFocus(any.pop());
        stop = true;
    } else if ('Home' === key) {
        fireFocus(getElement(targets, getParent(t)));
        stop = true;
    }
    stop && (offEventDefault(e), offEventPropagation(e));
}

function onKeyDownPages(e) {
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
        if ('ArrowDown' === key || 'Home' === key) {
            fireFocus(getElement(targets, t));
            stop = true;
        } else if ('ArrowUp' === key || 'End' === key) {
            any = [].slice.call(getElements(targets, t));
            fireFocus(any.pop());
            stop = true;
        }
    }
    stop && (offEventDefault(e), offEventPropagation(e));
}

export default onChange;