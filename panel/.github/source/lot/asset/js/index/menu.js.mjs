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

const targets = 'a[href]:not(.not\\:active)';

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
        menuLinks = getElements('.lot\\:menu[tabindex] ' + targets);
    if (menuParents && toCount(menuParents)) {
        menuParents.forEach(menuParent => {
            let menu = getElement('.lot\\:menu[tabindex]', menuParent),
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
    let menus = getElements('.lot\\:menu[tabindex]');
    menus && toCount(menus) && menus.forEach(menu => {
        offEvent('keydown', menu, onKeyDownMenus);
        onEvent('keydown', menu, onKeyDownMenus);
    });
} onChange();

function onClickDocument() {
    doHideMenus(0);
}

function onClickMenuShow(e) {
    if (e.defaultPrevented) {
        return;
    }
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
    if (e.defaultPrevented) {
        return;
    }
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
        if (parent = t.closest('.lot\\:menu[tabindex].is\\:enter')) {
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
                if (current = getElement(targets, next)) {
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
            if (current = t.closest('.lot\\:menu[tabindex].is\\:enter')) {
                // Hide menu then focus to the parent menu link
                if (current = getPrev(current)) {
                    fireEvent('click', current);
                    isFunction(current.focus) && current.focus();
                }
            }
        }
        stop = true;
    } else if ('End' === key) {
        if (parent = t.closest('.lot\\:menu[tabindex]')) {
            any = [].slice.call(getElements(targets, parent));
            if (current = any.pop()) {
                isFunction(current.focus) && current.focus();
            }
        }
        stop = true;
    } else if ('Home' === key) {
        if (parent = t.closest('.lot\\:menu[tabindex]')) {
            if (current = getElement(targets, parent)) {
                isFunction(current.focus) && current.focus();
            }
        }
        stop = true;
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
        keyIsShift = e.shiftKey, stop;
    if (t !== e.target) {
        return;
    }
    if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
        let any, current, next, parent, prev;
        if ('ArrowDown' === key || 'Home' === key) {
            if (current = getElement(targets, t)) {
                isFunction(current.focus) && current.focus();
            }
            stop = true;
        } else if ('ArrowUp' === key || 'End' === key) {
            any = [].slice.call(getElements(targets, t));
            if (current = any.pop()) {
                isFunction(current.focus) && current.focus();
            }
            stop = true;
        }
    }
    stop && (offEventDefault(e), offEventPropagation(e));
}

function onKeyDownMenuToggle(e) {
    if (e.defaultPrevented) {
        return;
    }
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
                if (current = getElement(targets, next)) {
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