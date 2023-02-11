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

const targets = ':scope>ul>li>:where(a,button,input,select,textarea,[tabindex]):not(:disabled):not([tabindex="-1"]):not(.not\\:active)';

function fireFocus(node) {
    node && isFunction(node.focus) && node.focus();
}

function onChange(init) {
    let sources = getElements('.lot\\:menus[tabindex]');
    sources && toCount(sources) && sources.forEach(source => {
        let menus = getElements(targets, source);
        menus && toCount(menus) && menus.forEach(menu => {
            onEvent('keydown', menu, onKeyDownMenu);
        });
        onEvent('keydown', source, onKeyDownMenus);
    });
    1 === init && W._.on('change', onChange);
}

function onKeyDownMenu(e) {
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
            if (parent = getParent(t, '.lot\\:menus[tabindex]')) {
                any = [].slice.call(getElements(targets, parent));
                fireFocus(any.pop());
            }
            stop = true;
        } else if ('Home' === key) {
            if (parent = getParent(t, '.lot\\:menus[tabindex]')) {
                fireFocus(getElement(targets, parent));
            }
            stop = true;
        }
    }
    stop && (offEventDefault(e), offEventPropagation(e));
}

function onKeyDownMenus(e) {
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

export default onChange;