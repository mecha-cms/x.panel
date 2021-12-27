import {
    W,
    getElement,
    getElements,
    getNext,
    getParent,
    getPrev,
    hasClass,
    setClass
} from '@taufik-nurrohman/document';

import {
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

const targets = ':scope>.lot\\:file[tabindex]:not(.has\\:event-file):not(.not\\:active),:scope>.lot\\:folder[tabindex]:not(.has\\:event-file):not(.not\\:active)';

function fireFocus(node) {
    node && isFunction(node.focus) && node.focus();
}

function onChange() {
    let sources = getElements('.lot\\:files[tabindex]:not(.has\\:event-files),.lot\\:folders[tabindex]:not(.has\\:event-files)');
    sources && toCount(sources) && sources.forEach(source => {
        setClass(source, 'has:event-files');
        let files = getElements(targets, source);
        files.forEach(file => {
            setClass(file, 'has:event-file');
            onEvent('keydown', file, onKeyDownFile);
        });
        onEvent('keydown', source, onKeyDownFiles);
    });
} onChange();

function onKeyDownFile(e) {
    if (e.defaultPrevented) {
        return;
    }
    let t = this,
        key = e.key,
        any, current, parent, next, prev, stop;
    if (t !== e.target) {
        return;
    }
    next = getNext(t);
    while (next && hasClass(next, 'not:active')) {
        next = getNext(next);
    }
    prev = getPrev(t);
    while (prev && hasClass(prev, 'not:active')) {
        prev = getPrev(prev);
    }
    if ('ArrowDown' === key) {
        fireFocus(next);
        stop = true;
    } else if ('ArrowUp' === key) {
        fireFocus(prev);
        stop = true;
    } else if ('End' === key) {
        any = [].slice.call(getElements(targets, getParent(t)));
        fireFocus(any.pop());
        stop = true;
    } else if ('Home' === key) {
        fireFocus(getElement(targets, getParent(t)));
        stop = true;
    }
    stop && (offEventDefault(e), offEventPropagation(e));
}

function onKeyDownFiles(e) {
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
        let any, current;
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

W._.on('change', onChange);