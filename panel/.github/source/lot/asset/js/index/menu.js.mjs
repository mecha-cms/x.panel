import {
    D,
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
        current, parent, next, prev;
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
        offEventDefault(e);
        offEventPropagation(e);
    } else if ('ArrowLeft' === key || 'Escape' === key) {
        parent = isFunction(t.closest) && t.closest('.lot\\:menu.is\\:enter');
        // Hide menu then focus to the parent menu link
        if (parent && (current = getPrev(parent))) {
            letClass(getParent(t), 'is:active');
            letClass(parent, 'is:enter');
            letClass(t, 'is:active');
            isFunction(current.focus) && current.focus();
        }
        offEventDefault(e);
        offEventPropagation(e);
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
        offEventDefault(e);
        offEventPropagation(e);
    } else if ('ArrowUp' === key) {
        current = prev && getChildFirst(prev);
        if (current && isFunction(current.focus)) {
            current.focus();
        } else {
            if (current = isFunction(t.closest) && t.closest('.is\\:enter')) {
                // Hide menu then focus to the parent menu link
                if (current = getPrev(current)) {
                    fireEvent('click', current);
                    isFunction(current.focus) && current.focus();
                }
            }
        }
        offEventDefault(e);
        offEventPropagation(e);
    } else if ('End' === key) {
        // TODO
    } else if ('Home' === key) {
        // TODO
    }
}

function onKeyDownMenuToggle(e) {
    let t = this,
        key = e.key,
        current,
        next = getNext(t),
        parent = getParent(t);
    if (next && parent && hasClass(next, 'lot:menu')) {
        if (' ' === key || 'Enter' === key) {
            fireEvent('click', t);
            offEventDefault(e);
            offEventPropagation(e);
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
            offEventDefault(e);
            offEventPropagation(e);
        } else if ('ArrowUp' === key) {
            // TODO
            offEventDefault(e);
            offEventPropagation(e);
        }
    }
}

W._.on('change', onChange);