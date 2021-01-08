import {D, W, getElement, getElements, getParent, getNext, getPrev, letClass, setClass, toggleClass} from '@taufik-nurrohman/document';
import {eventPreventDefault, eventStopPropagation, off, on} from '@taufik-nurrohman/event';
import {toCount} from '@taufik-nurrohman/to';

function doHideMenus(but) {
    getElements('.lot\\:menu.is\\:enter').forEach(node => {
        if (but !== node) {
            letClass(node, 'is:enter');
            letClass(getParent(node), 'is:active');
            letClass(getPrev(node), 'is:active');
        }
    });
}

function onClickHideMenus() {
    doHideMenus(0);
}

function onClickShowMenu(e) {
    let t = this,
        current = getNext(t);
    doHideMenus(current);
    W.setTimeout(() => {
        toggleClass(t, 'is:active');
        toggleClass(getParent(t), 'is:active');
        toggleClass(current, 'is:enter');
    }, 1);
    eventPreventDefault(e);
    eventStopPropagation(e);
}

export function hook() {
    off('click', D, onClickHideMenus);
    let menuParents = getElements('.has\\:menu');
    if (toCount(menuParents)) {
        menuParents.forEach(menuParent => {
            let menu = getElement('.lot\\:menu', menuParent),
                a = getPrev(menu);
            if (menu && a) {
                on('click', a, onClickShowMenu);
            }
        });
        on('click', D, onClickHideMenus);
    }
}
