import {
    D,
    W,
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
    toggleClass
} from '@taufik-nurrohman/document';

import {
    offEvent,
    offEventDefault,
    offEventPropagation,
    onEvent
} from '@taufik-nurrohman/event';

import {
    toCount
} from '@taufik-nurrohman/to';

function _hideMenus(but) {
    getElements('.lot\\:menu.is\\:enter').forEach(node => {
        if (but !== node) {
            letClass(node, 'is:enter');
            letClass(getParent(node), 'is:active');
            letClass(getPrev(node), 'is:active');
        }
    });
}

function _clickHideMenus() {
    _hideMenus(0);
}

function _clickShowMenu(e) {
    let t = this,
        current = getNext(t);
    _hideMenus(current);
    W.setTimeout(() => {
        toggleClass(t, 'is:active');
        toggleClass(getParent(t), 'is:active');
        toggleClass(current, 'is:enter');
    }, 1);
    offEventDefault(e);
    offEventPropagation(e);
}

function onChange() {
    offEvent('click', D, _clickHideMenus);
    let menuParents = getElements('.has\\:menu');
    if (menuParents && toCount(menuParents)) {
        menuParents.forEach(menuParent => {
            let menu = getElement('.lot\\:menu', menuParent),
                a = getPrev(menu);
            if (menu && a) {
                onEvent('click', a, _clickShowMenu);
            }
        });
        onEvent('click', D, _clickHideMenus);
    }
} onChange();

W._.on('change', onChange);