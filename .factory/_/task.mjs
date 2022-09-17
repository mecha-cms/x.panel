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

const targets = 'a[href]:not([tabindex="-1"]):not(.not\\:active),button:not(:disabled):not([tabindex="-1"]):not(.not\\:active),input:not(:disabled):not([tabindex="-1"]):not(.not\\:active),select:not(:disabled):not([tabindex="-1"]):not(.not\\:active),[tabindex]:not([tabindex="-1"]):not(.not\\:active)';

function fireFocus(node) {
    node && isFunction(node.focus) && node.focus();
}

function fireSelect(node) {
    node && isFunction(node.select) && node.select();
}

function onChange(init) {
    let sources = getElements('.lot\\:tasks[tabindex]');
    sources && toCount(sources) && sources.forEach(source => {
        let tasks = getElements(targets, source);
        tasks && toCount(tasks) && tasks.forEach(task => {
            onEvent('keydown', task, onKeyDownTask);
        });
        onEvent('keydown', source, onKeyDownTasks);
    });
    1 === init && W._.on('change', onChange);
}

function onKeyDownTask(e) {
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
        next = getNext(t);
        while (next && hasClass(next, 'not:active')) {
            next = getNext(next);
        }
        prev = getPrev(t);
        while (prev && hasClass(prev, 'not:active')) {
            prev = getPrev(prev);
        }
        if ('ArrowLeft' === key) {
            if (stop = !('selectionStart' in t && 0 !== t.selectionStart)) {
                fireFocus(prev), fireSelect(prev);
            }
        } else if ('ArrowRight' === key) {
            if (stop = !('selectionEnd' in t && t.selectionEnd < toCount(t.value || ""))) {
                fireFocus(next), fireSelect(next);
            }
        } else if ('End' === key) {
            stop = !('selectionEnd' in t && toCount(t.value || ""));
            if (stop && (parent = getParent(t, '.lot\\:tasks[tabindex]'))) {
                any = [].slice.call(getElements(targets, parent));
                if (current = any.pop()) {
                    fireFocus(current), fireSelect(current);
                }
            }
        } else if ('Home' === key) {
            stop = !('selectionStart' in t && toCount(t.value || ""));
            if (stop && (parent = getParent(t, '.lot\\:tasks[tabindex]'))) {
                if (current = getElement(targets, parent)) {
                    fireFocus(current), fireSelect(current);
                }
            }
        }
    }
    stop && (offEventDefault(e), offEventPropagation(e));
}

function onKeyDownTasks(e) {
    if (e.defaultPrevented) {
        return;
    }
    let t = this,
        key = e.key,
        keyIsAlt = e.altKey,
        keyIsCtrl = e.ctrlKey,
        keyIsShift = e.shiftKey,
        any, current, stop;
    if (t !== e.target) {
        return;
    }
    if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
        if ('ArrowLeft' === key || 'End' === key) {
            any = [].slice.call(getElements(targets, t));
            if (current = any.pop()) {
                fireFocus(current), fireSelect(current);
            }
            stop = true;
        } else if ('ArrowRight' === key || 'Home' === key) {
            if (current = getElement(targets, t)) {
                fireFocus(current), fireSelect(current);
            }
            stop = true;
        }
    }
    stop && (offEventDefault(e), offEventPropagation(e));
}

export default onChange;