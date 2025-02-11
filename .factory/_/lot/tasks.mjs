import {
    fireFocus,
    fireSelect,
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
    hasState,
    setClass
} from '@taufik-nurrohman/document';

import {
    offEventDefault,
    offEventPropagation
} from '@taufik-nurrohman/event';

import {
    toCount
} from '@taufik-nurrohman/to';

const targets = ':scope>:where(a,button,input,select,textarea,[tabindex]):not(:disabled):not([tabindex="-1"]):not(.not\\:active)';

function onChange(init) {
    let sources = getElements('.lot\\:tasks[tabindex]');
    sources && toCount(sources) && sources.forEach(source => {
        let tasks = getElements(targets, source);
        tasks && toCount(tasks) && tasks.forEach(task => {
            onEventOnly('keydown', task, onKeyDownTask);
        });
        onEventOnly('keydown', source, onKeyDownTasks);
    });
    1 === init && W._.on('change', onChange);
}

function onKeyDownTask(e) {
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
            if (stop = !(hasState(t, 'selectionStart') && 0 !== t.selectionStart)) {
                fireFocus(prev), fireSelect(prev);
            }
        } else if ('ArrowRight' === key) {
            if (stop = !(hasState(t, 'selectionEnd') && t.selectionEnd < toCount(t.value || ""))) {
                fireFocus(next), fireSelect(next);
            }
        } else if ('End' === key) {
            stop = !(hasState(t, 'selectionEnd') && toCount(t.value || ""));
            if (stop && (parent = getParent(t, '.lot\\:tasks[tabindex]'))) {
                any = [].slice.call(getElements(targets, parent));
                if (current = any.pop()) {
                    fireFocus(current), fireSelect(current);
                }
            }
        } else if ('Home' === key) {
            stop = !(hasState(t, 'selectionStart') && toCount(t.value || ""));
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