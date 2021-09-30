import {
    W,
    getChildFirst,
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
    setClass,
    setClasses,
    setElement,
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

function onChange() {
    let sources = getElements('.lot\\:tabs');
    sources && toCount(sources) && sources.forEach(source => {
        let panes = [].slice.call(getChildren(source)),
            tabs = [].slice.call(getElements('a[target^="tab:"]', panes.shift())),
            input = setElement('input'), name, value;
        input.type = 'hidden';
        input.name = name = getDatum(source, 'name');
        setChildLast(source, input);
        function onClick(e) {
            let t = this,
                parent = getParent(t);
            if (!hasClass(parent, 'has:link')) {
                if (!hasClass(t, 'not:active')) {
                    tabs.forEach(tab => {
                        if (tab !== t) {
                            letClass(tab, 'is:current');
                            letClass(getParent(tab), 'is:current');
                            if (panes[tab._tabIndex]) {
                                letClass(panes[tab._tabIndex], 'is:current');
                            }
                        }
                    });
                    if (hasClass(parent, 'can:toggle')) {
                        toggleClass(t, 'is:current');
                        toggleClass(parent, 'is:current');
                        if (panes[t._tabIndex]) {
                            toggleClass(panes[t._tabIndex], 'is:current');
                            input.value = value = hasClass(t, 'is:current') ? getDatum(t, 'value') : null;
                        }
                    } else {
                        setClass(t, 'is:current');
                        setClass(parent, 'is:current');
                        if (panes[t._tabIndex]) {
                            setClass(panes[t._tabIndex], 'is:current');
                            input.value = value = getDatum(t, 'value');
                        }
                    }
                    W._.fire('change.tab', [name, value]);
                }
                offEventDefault(e);
            }
        }
        tabs.forEach((tab, index) => {
            tab._tabIndex = index;
            onEvent('click', tab, onClick);
            onEvent('keydown', tab, onKeyDownTab);
        });
        let tabCurrent = tabs.find((value, key) => 0 !== key && hasClass(getParent(value), 'is:current'));
        if (tabCurrent) {
            input.value = getDatum(tabCurrent, 'name');
        }
        onEvent('keydown', source, onKeyDownTabs);
    });
} onChange();

function onKeyDownTab(e) {
    let t = this,
        key = e.key,
        keyIsAlt = e.altKey,
        keyIsCtrl = e.ctrlKey;
    if (!keyIsAlt && !keyIsCtrl) {
        let current, next, parent, prev;
        if ('ArrowDown' === key) {
            if (hasClass(t, 'can:toggle') && !hasClass(t, 'is:current')) {
                current = t;
            } else {
                if (parent = getParent(t)) {
                    next = getNext(parent);
                    while (next && hasClass(next, 'not:active')) {
                        next = getNext(next);
                    }
                }
                current = next && getChildFirst(next);
            }
            if (current) {
                fireEvent('click', current);
                isFunction(current.focus) && current.focus();
            }
            offEventDefault(e);
            offEventPropagation(e);
        } else if ('ArrowLeft' === key || 'PageUp' === key) {
            if (parent = getParent(t)) {
                prev = getPrev(parent);
                while (prev && hasClass(prev, 'not:active')) {
                    prev = getPrev(prev);
                }
            }
            if (current = prev && getChildFirst(prev)) {
                fireEvent('click', current);
                isFunction(current.focus) && current.focus();
            }
            offEventDefault(e);
            offEventPropagation(e);
        } else if ('ArrowRight' === key || 'PageDown' === key) {
            if (parent = getParent(t)) {
                next = getNext(parent);
                while (next && hasClass(next, 'not:active')) {
                    next = getNext(next);
                }
            }
            if (current = next && getChildFirst(next)) {
                fireEvent('click', current);
                isFunction(current.focus) && current.focus();
            }
            offEventDefault(e);
            offEventPropagation(e);
        } else if ('ArrowUp' === key) {
            if (hasClass(t, 'can:toggle') && hasClass(t, 'is:current')) {
                current = t;
            } else {
                if (parent = getParent(t)) {
                    prev = getPrev(parent);
                    while (prev && hasClass(prev, 'not:active')) {
                        prev = getPrev(prev);
                    }
                }
                current = prev && getChildFirst(prev);
            }
            if (current) {
                fireEvent('click', current);
                isFunction(current.focus) && current.focus();
            }
            offEventDefault(e);
            offEventPropagation(e);
        } else if (' ' === key || 'Enter' === key) {
            if (hasClass(t, 'can:toggle')) {
                fireEvent('click', t);
                isFunction(t.focus) && t.focus();
            }
            offEventDefault(e);
            offEventPropagation(e);
        } else if ('Escape' === key) {
            if (isFunction(t.closest) && (parent = t.closest('.lot\\:tabs'))) {
                isFunction(parent.focus) && parent.focus();
            }
            offEventDefault(e);
            offEventPropagation(e);
        }
    }
}

function onKeyDownTabs(e) {
    let t = this,
        key = e.key,
        keyIsAlt = e.altKey,
        keyIsCtrl = e.ctrlKey,
        keyIsShift = e.shiftKey;
    if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
        let current, next, parent, prev;
        if ('ArrowDown' === key || 'ArrowRight' === key || 'PageDown' === key) {
            if (current = getElement('a[target^="tab:"]:not(.not\\:active)', t)) {
                isFunction(current.focus) && current.focus();
            }
            offEventDefault(e);
            offEventPropagation(e);
        } else if ('ArrowUp' === key || 'ArrowLeft' === key || 'PageUp' === key) {
            let links = [].slice.call(getElements('a[target^="tab:"]:not(.not\\:active)', t));
            if (current = links.pop()) {
                isFunction(current.focus) && current.focus();
            }
            offEventDefault(e);
            offEventPropagation(e);
        }
    }
}

W._.on('change', onChange);