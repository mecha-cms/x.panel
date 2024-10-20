import {
    fireFocus,
    onEventOnly
} from '../../_.mjs';

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
    offEventDefault,
    offEventPropagation
} from '@taufik-nurrohman/event';

import {
    toCount
} from '@taufik-nurrohman/to';

const targets = ':where(a,[tabindex]):not(.not\\:active)';

function doHideMenus(but, trigger) {
    getElements('.lot\\:menu[tabindex].is\\:enter').forEach(node => {
        if (but !== node) {
            letClass(getParent(node), 'is:active');
            letClass(getPrev(node), 'is:active');
            letClass(node, 'is:enter');
            if (trigger) {
                setAttribute(trigger, 'aria-expanded', 'false');
            }
            W._.fire('menu.exit', [], node);
        }
    });
}

function onChange(init) {
    let menuParents = getElements('.has\\:menu'),
        menuLinks = getElements('.lot\\:menu[tabindex]>ul>li>' + targets);
    if (menuParents && toCount(menuParents)) {
        menuParents.forEach(menuParent => {
            let menu = getElement('.lot\\:menu[tabindex]', menuParent),
                a = getPrev(menu);
            if (menu && a) {
                onEventOnly('click', a, onClickMenuShow);
                onEventOnly('keydown', a, onKeyDownMenuToggle);
            }
        });
        onEventOnly('click', D, onClickDocument);
    }
    if (menuLinks && toCount(menuLinks)) {
        menuLinks.forEach(menuLink => {
            onEventOnly('keydown', menuLink, onKeyDownMenu);
        });
    }
    let sources = getElements('.lot\\:menu[tabindex]');
    sources && toCount(sources) && sources.forEach(source => {
        onEventOnly('keydown', source, onKeyDownMenus);
    });
    1 === init && W._.on('change', onChange);
}

function onClickDocument() {
    doHideMenus(0);
}

function onClickMenuShow(e) {
    offEventDefault(e);
    offEventPropagation(e);
    let t = this,
        current = getNext(t), next;
    doHideMenus(current, t);
    W.setTimeout(() => {
        toggleClass(current, 'is:enter');
        toggleClass(getParent(t), 'is:active');
        toggleClass(t, 'is:active');
        setAttribute(t, 'aria-expanded', hasClass(t, 'is:active') ? 'true' : 'false');
        W._.fire('menu.enter', [], current);
    }, 1);
}

function onKeyDownMenu(e) {
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
            setAttribute(getPrev(parent), 'aria-expanded', 'false');
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
            setAttribute(t, 'aria-expanded', 'true');
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
        if (current = prev && getChildFirst(prev)) {
            fireFocus(current);
        } else {
            if (current = getParent(t, '.lot\\:menu[tabindex].is\\:enter')) {
                // Apply only to the first level drop-down menu
                if (hasClass(current, 'level:1')) {
                    // Hide menu then focus to the parent menu link
                    letClass(current, 'is:enter');
                    if (current = getPrev(current)) {
                        letClass(current, 'is:active');
                        letClass(getParent(current), 'is:active');
                        setAttribute(current, 'aria-expanded', 'false');
                        W.setTimeout(() => {
                            fireFocus(current);
                        }, 1);
                    }
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
                W.setTimeout(() => {
                    // Focus to the first link of child menu
                    fireFocus(getElement(targets, next));
                }, 1);
                stop = true;
            }
        // Apply only to the first level drop-down menu
        } else if ('ArrowDown' === key && hasClass(next, 'level:1')) {
            setAttribute(t, 'aria-expanded', 'true');
            setClass(getParent(t), 'is:active');
            setClass(next, 'is:enter');
            setClass(t, 'is:active');
            W.setTimeout(() => {
                // Focus to the first link of child menu
                fireFocus(getElement(targets, next));
            }, 1);
            stop = true;
        }
    }
    stop && (offEventDefault(e), offEventPropagation(e));
}

export default onChange;