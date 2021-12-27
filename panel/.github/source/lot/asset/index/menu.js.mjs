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
    setAttribute,
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

function fireFocus(node) {
    node && isFunction(node.focus) && node.focus();
}

function doHideMenus(but, trigger) {
    getElements('.lot\\:menu[tabindex].is\\:enter').forEach(node => {
        if (but !== node) {
            letClass(getParent(node), 'is:active');
            letClass(getPrev(node), 'is:active');
            letClass(node, 'is:enter');
            if (trigger) {
                setAttribute(trigger, 'aria-expanded', 'false');
            }
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
            if (menu && a && !hasClass(a, 'has:event-menu-item')) {
                setClass(a, 'has:event-menu-item');
                onEvent('click', a, onClickMenuShow);
                onEvent('keydown', a, onKeyDownMenuToggle);
            }
        });
        onEvent('click', D, onClickDocument);
    }
    if (menuLinks && toCount(menuLinks)) {
        menuLinks.forEach(menuLink => {
            if (!hasClass(menuLink, 'has:event-menu-item')) {
                setClass(menuLink, 'has:event-menu-item');
                onEvent('keydown', menuLink, onKeyDownMenu);
            }
        });
    }
    let sources = getElements('.lot\\:menu[tabindex]:not(.has\\:event-menu)');
    sources && toCount(sources) && sources.forEach(source => {
        setClass(source, 'has:event-menu');
        onEvent('keydown', source, onKeyDownMenus);
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
    doHideMenus(current, t);
    W.setTimeout(() => {
        toggleClass(current, 'is:enter');
        toggleClass(getParent(t), 'is:active');
        toggleClass(t, 'is:active');
        setAttribute(t, 'aria-expanded', hasClass(t, 'is:active') ? 'true' : 'false');
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
        while (next && (hasClass(next, 'as:separator') || hasClass(next, 'not:active'))) {
            next = getNext(next);
        }
        prev = getPrev(parent);
        while (prev && (hasClass(prev, 'as:separator') || hasClass(prev, 'not:active'))) {
            prev = getPrev(prev);
        }
    }
    if ('ArrowDown' === key) {
        fireFocus(next && getChildFirst(next));
        stop = true;
    } else if ('ArrowLeft' === key || 'Escape' === key || 'Tab' === key) {
        // Hide menu then focus to the parent menu link
        if (parent = getParent(t, '.lot\\:menu[tabindex].is\\:enter')) {
            letClass(getParent(t), 'is:active');
            letClass(parent, 'is:enter');
            letClass(t, 'is:active');
            if ('Tab' !== key) {
                fireFocus(getPrev(parent));
            }
        // Focus to the self menu
        } else if ('Escape' === key) {
            fireFocus(getParent(t, '.lot\\:menu[tabindex]'));
        }
        stop = 'Tab' !== key;
    } else if ('ArrowRight' === key) {
        next = getNext(t);
        if (next && hasClass(next, 'lot:menu')) {
            setClass(getParent(t), 'is:active');
            setClass(next, 'is:enter');
            setClass(t, 'is:active');
            W.setTimeout(() => {
                // Focus to the first link of child menu
                fireFocus(getElement(targets, next));
            }, 1);
        }
        stop = true;
    } else if ('ArrowUp' === key) {
        current = prev && getChildFirst(prev);
        if (current) {
            fireFocus(current);
        } else {
            if (current = getParent(t, '.lot\\:menu[tabindex].is\\:enter')) {
                // Hide menu then focus to the parent menu link
                if (current = getPrev(current)) {
                    fireEvent('click', current), fireFocus(current);
                }
            }
        }
        stop = true;
    } else if ('End' === key) {
        if (parent = getParent(t, '.lot\\:menu[tabindex]')) {
            any = [].slice.call(getElements(targets, parent));
            fireFocus(any.pop());
        }
        stop = true;
    } else if ('Home' === key) {
        if (parent = getParent(t, '.lot\\:menu[tabindex]')) {
            fireFocus(getElement(targets, parent));
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
                // Focus to the first link of child menu
                fireFocus(getElement(targets, next));
            }, 1);
            stop = true;
        }
    }
    stop && (offEventDefault(e), offEventPropagation(e));
}

W._.on('change', onChange);