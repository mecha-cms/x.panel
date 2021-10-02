import {
    D,
    W,
    getChildFirst,
    getElement,
    getElements,
    getNext,
    getParent,
    getPrev,
    hasClass
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

const targets = 'a[href]:not(.not\\:active),button:not(:disabled):not(.not\\:active),input:not(:disabled):not(.not\\:active),select:not(:disabled):not(.not\\:active)';

function onChange() {
    let tasks = getElements('.lot\\:tasks');
    tasks && toCount(tasks) && tasks.forEach(task => {
        let taskButtons = getElements(targets, task);
        taskButtons && toCount(taskButtons) && taskButtons.forEach(taskButton => {
            offEvent('keydown', taskButton, onKeyDownTask);
            onEvent('keydown', taskButton, onKeyDownTask);
        });
        offEvent('keydown', task, onKeyDownTasks);
        onEvent('keydown', task, onKeyDownTasks);
    });
} onChange();

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
            prev && isFunction(prev.focus) && prev.focus();
            stop = true;
        } else if ('ArrowRight' === key) {
            next && isFunction(next.focus) && next.focus();
            stop = true;
        } else if ('End' === key) {
            if (parent = t.closest('.lot\\:tasks')) {
                any = [].slice.call(getElements(targets, parent));
                if (current = any.pop()) {
                    isFunction(current.focus) && current.focus();
                }
            }
            stop = true;
        } else if ('Home' === key) {
            if (parent = t.closest('.lot\\:tasks')) {
                if (current = getElement(targets, parent)) {
                    isFunction(current.focus) && current.focus();
                }
            }
            stop = true;
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
        keyIsShift = e.shiftKey, stop;
    if (t !== e.target) {
        return;
    }
    if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
        let any, current, next, parent, prev;
        if ('ArrowLeft' === key || 'End' === key) {
            any = [].slice.call(getElements(targets, t));
            if (current = any.pop()) {
                isFunction(current.focus) && current.focus();
            }
            stop = true;
        } else if ('ArrowRight' === key || 'Home' === key) {
            if (current = getElement(targets, t)) {
                isFunction(current.focus) && current.focus();
            }
            stop = true;
        }
    }
    stop && (offEventDefault(e), offEventPropagation(e));
}

W._.on('change', onChange);