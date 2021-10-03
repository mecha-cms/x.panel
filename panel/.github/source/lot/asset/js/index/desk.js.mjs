import {
    R,
    W,
    getElement,
    getElements,
    getFormElement,
    getNext,
    getParent,
    getPrev,
    hasClass
} from '@taufik-nurrohman/document';

import {
    fireEvent,
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

const mainSearchForm = getFormElement('get');
const mainSearchFormInput = mainSearchForm && mainSearchForm.q;

const targets = ':scope>[tabindex]:not(.not\\:active)';

function fireFocus(node) {
    node && isFunction(node.focus) && node.focus();
}

function onChange() {
    let sources = getElements('.lot\\:desk[tabindex]');
    sources && toCount(sources) && sources.forEach(source => {
        let childs = getElements(targets, source);
        childs && toCount(childs) && childs.forEach(child => {
            onEvent('keydown', child, onKeyDownDeskChild);
        });
        onEvent('keydown', source, onKeyDownDesk);
    });
} onChange();

function onKeyDownDesk(e) {
    // Since removing events is not possible here, checking if another event has been added is the only way
    // to prevent the declaration below from executing if previous events have blocked it.
    if (e.defaultPrevented) {
        return;
    }
    let t = this,
        key = e.key,
        keyIsAlt = e.altKey,
        keyIsCtrl = e.ctrlKey,
        keyIsShift = e.shiftKey,
        any, next, stop;
    if (t !== e.target) {
        return;
    }
    let isFlex = hasClass(t, 'flex');
    if (!keyIsAlt && keyIsCtrl) {
        if ('?' === key) {
            console.info('TODO: Go to the about page.');
            stop = true;
        } else if ('f' === key) {
            fireFocus(mainSearchFormInput);
            stop = true;
        }
    } else if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
        if ('Arrow' + (isFlex ? 'Left' : 'Up') === key || 'End' === key) {
            any = [].slice.call(getElements(targets, t));
            fireFocus(any.pop());
            stop = true;
        } else if ('Arrow' + (isFlex ? 'Right' : 'Down') === key || 'Home' === key) {
            fireFocus(getElement(targets, t));
            stop = true;
        } else if ('F6' === key) {
            // Focus back to `<html>` and continue with the default `F6` key function.
            // Usually to switch between address bar and window contents in a web browser.
            fireFocus(R);
            stop = true;
        } else if ('F10' === key) {
            if (next = (getElement('.lot\\:bar[tabindex] a[href]:not(.not\\:active)') || getElement('.lot\\:bar[tabindex]'))) {
                if (hasClass(getParent(next), 'has:menu')) {
                    fireEvent('click', next);
                }
                fireFocus(next);
            }
            stop = true;
        }
    }
    stop && (offEventDefault(e), offEventPropagation(e));
}

function onKeyDownDeskChild(e) {
    if (e.defaultPrevented) {
        return;
    }
    let t = this,
        key = e.key,
        keyIsAlt = e.altKey,
        keyIsCtrl = e.ctrlKey,
        keyIsShift = e.shiftKey,
        any, current, parent, next, prev, stop;
    if (t !== e.target) {
        return;
    }
    let isFlex = hasClass(getParent(t), 'flex');
    if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
        next = getNext(t);
        while (next && hasClass(next, 'not:active')) {
            next = getNext(next);
        }
        prev = getPrev(t);
        while (prev && hasClass(prev, 'not:active')) {
            prev = getPrev(prev);
        }
        if ('Arrow' + (isFlex ? 'Left' : 'Up') === key) {
            fireFocus(prev);
            stop = true;
        } else if ('Arrow' + (isFlex ? 'Right' : 'Down') === key) {
            fireFocus(next);
            stop = true;
        } else if ('End' === key) {
            if (parent = t.closest('.lot\\:desk[tabindex]')) {
                any = [].slice.call(getElements(targets, parent));
                fireFocus(any.pop());
            }
            stop = true;
        } else if ('Escape' === key) {
            fireFocus(t.closest('.lot\\:desk[tabindex]'));
            stop = true;
        } else if ('Home' === key) {
            if (parent = t.closest('.lot\\:desk[tabindex]')) {
                fireFocus(getElement(targets, parent));
            }
            stop = true;
        }
    }
    stop && (offEventDefault(e), offEventPropagation(e));
}

W._.on('change', onChange);