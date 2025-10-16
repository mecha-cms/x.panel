import {
    fireFocus,
    onEventOnly
} from '../../_.mjs';

import {
    D,
    W,
    getAttribute,
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
    offEventPropagation
} from '@taufik-nurrohman/event';

import {
    toCount
} from '@taufik-nurrohman/to';

const targets = ':scope>ul>li>:where(a,button,input,select,textarea,[tabindex]):not(:disabled):not([tabindex="-1"]):not(.not-active)';

function onChange(init) {
    let sources = getElements('.lot-links[tabindex]');
    sources && toCount(sources) && sources.forEach(source => {
        let links = getElements(targets, source);
        links && toCount(links) && links.forEach(link => {
            onEventOnly('keydown', link, onKeyDownLink);
        });
        onEventOnly('keydown', source, onKeyDownLinks);
    });
    1 === init && W._.on('change', onChange);
}

function onKeyDownLink(e) {
    let t = this,
        key = e.key,
        keyIsAlt = e.altKey,
        keyIsCtrl = e.ctrlKey,
        keyIsShift = e.shiftKey,
        any, current, parent, next, prev, stop, vertical;
    if (parent = getParent(t, '[aria-orientation]')) {
        vertical = 'v' === (getAttribute(parent, 'aria-orientation') || [""])[0];
    }
    if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
        if (parent = getParent(t)) {
            next = getNext(parent);
            while (next && (hasClass(next, 'as-separator') || hasClass(next, 'not-active'))) {
                next = getNext(next);
            }
            prev = getPrev(parent);
            while (prev && (hasClass(prev, 'as-separator') || hasClass(prev, 'not-active'))) {
                prev = getPrev(prev);
            }
        }
        if ('Arrow' + (vertical ? 'Up' : 'Left') === key) {
            fireFocus(prev && getChildFirst(prev));
            stop = true;
        } else if ('Arrow' + (vertical ? 'Down' : 'Right') === key) {
            fireFocus(next && getChildFirst(next));
            stop = true;
        } else if ('End' === key) {
            if (parent = getParent(t, '.lot-links[tabindex]')) {
                any = [].slice.call(getElements(targets, parent));
                fireFocus(any.pop());
            }
            stop = true;
        } else if ('Home' === key) {
            if (parent = getParent(t, '.lot-links[tabindex]')) {
                fireFocus(getElement(targets, parent));
            }
            stop = true;
        }
    }
    stop && (offEventDefault(e), offEventPropagation(e));
}

function onKeyDownLinks(e) {
    let t = this,
        key = e.key,
        keyIsAlt = e.altKey,
        keyIsCtrl = e.ctrlKey,
        keyIsShift = e.shiftKey,
        any, stop, vertical;
    if (t !== e.target) {
        return;
    }
    vertical = 'v' === (getAttribute(t, 'aria-orientation') || [""])[0];
    if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
        if ('Arrow' + (vertical ? 'Down' : 'Right') === key || 'Home' === key) {
            fireFocus(getElement(targets, t));
            stop = true;
        } else if ('Arrow' + (vertical ? 'Up' : 'Left') === key || 'End' === key) {
            any = [].slice.call(getElements(targets, t));
            fireFocus(any.pop());
            stop = true;
        }
    }
    stop && (offEventDefault(e), offEventPropagation(e));
}

export default onChange;