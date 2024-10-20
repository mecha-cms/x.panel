import {
    fireFocus,
    onEventOnly
} from '../../_.mjs';

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
    offEventPropagation
} from '@taufik-nurrohman/event';

import {
    toCount
} from '@taufik-nurrohman/to';

const targets = ':scope>:where(.lot\\:file[tabindex],.lot\\:folder[tabindex]):not([tabindex="-1"]):not(.not\\:active)';

function onChange(init) {
    let sources = getElements(':where(.lot\\:files,.lot\\:folders)[tabindex]');
    sources && toCount(sources) && sources.forEach(source => {
        let files = getElements(targets, source);
        files.forEach(file => {
            onEventOnly('keydown', file, onKeyDownFile);
        });
        onEventOnly('keydown', source, onKeyDownFiles);
    });
    1 === init && W._.on('change', onChange);
}

function onKeyDownFile(e) {
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

export default onChange;