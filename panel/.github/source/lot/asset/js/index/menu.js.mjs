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
    letClass,
    setClass,
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

function doHideMenus(but) {
    getElements('.lot\\:menu.is\\:enter').forEach(node => {
        if (but !== node) {
            letClass(getParent(node), 'is:active');
            letClass(getPrev(node), 'is:active');
            letClass(node, 'is:enter');
        }
    });
}

function onChange() {
    offEvent('click', D, onClickDocument);
    let menuParents = getElements('.has\\:menu'),
        menuLinks = getElements('.lot\\:menu a[href]:not(.not\\:active)');
    if (menuParents && toCount(menuParents)) {
        menuParents.forEach(menuParent => {
            let menu = getElement('.lot\\:menu', menuParent),
                a = getPrev(menu);
            if (menu && a) {
                onEvent('click', a, onClickMenuShow);
                onEvent('keydown', a, onKeyDownMenuToggle);
            }
        });
        onEvent('click', D, onClickDocument);
    }
    if (menuLinks && toCount(menuLinks)) {
        menuLinks.forEach(menuLink => {
            offEvent('keydown', menuLink, onKeyDownMenu);
            onEvent('keydown', menuLink, onKeyDownMenu);
        });
    }
    let menus = getElements('.lot\\:menu');
    menus && toCount(menus) && menus.forEach(menu => {
        offEvent('keydown', menu, onKeyDownMenus);
        onEvent('keydown', menu, onKeyDownMenus);
    });
} onChange();

function onClickDocument() {
    doHideMenus(0);
}

function onClickMenuShow(e) {
    let t = this,
        current = getNext(t), next;
    doHideMenus(current);
    W.setTimeout(() => {
        toggleClass(current, 'is:enter');
        toggleClass(getParent(t), 'is:active');
        toggleClass(t, 'is:active');
    }, 1);
    offEventDefault(e);
    offEventPropagation(e);
}

function onKeyDownMenu(e) {
    let t = this,
        key = e.key,
        any, current, parent, next, prev, stop;
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
    if ('ArrowDown' === key) {
        current = next && getChildFirst(next);
        if (current && isFunction(current.focus)) {
            current.focus();
        }
        stop = true;
    } else if ('ArrowLeft' === key || 'Escape' === key || 'Tab' === key) {
        // Hide menu then focus to the parent menu link
        if (parent = t.closest('.lot\\:menu.is\\:enter')) {
            letClass(getParent(t), 'is:active');
            letClass(parent, 'is:enter');
            letClass(t, 'is:active');
            if ('Tab' !== key && (current = getPrev(parent))) {
                isFunction(current.focus) && current.focus();
            }
        // Focus to the self menu
        } else if ('Escape' === key && (parent = t.closest('.lot\\:menu'))) {
            isFunction(parent.focus) && parent.focus();
        }
        stop = 'Tab' !== key;
    } else if ('ArrowRight' === key) {
        next = getNext(t);
        if (next && hasClass(next, 'lot:menu')) {
            setClass(getParent(t), 'is:active');
            setClass(next, 'is:enter');
            setClass(t, 'is:active');
            W.setTimeout(() => {
                if (current = getElement('a[href]:not(.not\\:active)', next)) {
                    // Focus to the first link of child menu
                    isFunction(current.focus) && current.focus();
                }
            }, 1);
        }
        stop = true;
    } else if ('ArrowUp' === key) {
        current = prev && getChildFirst(prev);
        if (current && isFunction(current.focus)) {
            current.focus();
        } else {
            if (current = t.closest('.is\\:enter')) {
                // Hide menu then focus to the parent menu link
                if (current = getPrev(current)) {
                    fireEvent('click', current);
                    isFunction(current.focus) && current.focus();
                }
            }
        }
        stop = true;
    } else if ('End' === key) {
        if (parent = t.closest('.lot\\:menu')) {
            any = [].slice.call(getElements('a[href]:not(.not\\:active)', parent));
            if (current = any.pop()) {
                isFunction(current.focus) && current.focus();
            }
        }
        stop = true;
    } else if ('Home' === key) {
        if (parent = t.closest('.lot\\:menu')) {
            if (current = getElement('a[href]:not(.not\\:active)', parent)) {
                isFunction(current.focus) && current.focus();
            }
        }
        stop = true;
    }
    stop && (offEventDefault(e), offEventPropagation(e));
}

function onKeyDownMenus(e) {
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
            if (current = getElement('a[href]:not(.not\\:active)', t)) {
                isFunction(current.focus) && current.focus();
            }
            stop = true;
        } else if ('ArrowUp' === key || 'End' === key) {
            any = [].slice.call(getElements('a[href]:not(.not\\:active)', t));
            if (current = any.pop()) {
                isFunction(current.focus) && current.focus();
            }
            stop = true;
        }
    }
    stop && (offEventDefault(e), offEventPropagation(e));
}

function onKeyDownMenuToggle(e) {
    let t = this,
        key = e.key,
        current,
        next = getNext(t),
        parent = getParent(t), stop;
    if (next && parent && hasClass(next, 'lot:menu')) {
        if (' ' === key || 'Enter' === key || 'Tab' === key) {
            if ('Tab' === key) {
                hasClass(next, 'is:enter') && fireEvent('click', t);
            } else {
                fireEvent('click', t);
                stop = true;
            }
        } else if ('ArrowDown' === key) {
            if (!hasClass(next, 'is:enter')) {
                fireEvent('click', t);
            }
            W.setTimeout(() => {
                if (current = getElement('a[href]:not(.not\\:active)', next)) {
                    // Focus to the first link of child menu
                    isFunction(current.focus) && current.focus();
                }
            }, 1);
            stop = true;
        }
    }
    stop && (offEventDefault(e), offEventPropagation(e));
}

W._.on('change', onChange);