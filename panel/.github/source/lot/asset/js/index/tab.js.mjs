import {
    W,
    getChildFirst,
    getChildren,
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

const targets = 'a[target^="tab:"]:not(.not\\:active)';

function onChange() {
    let sources = getElements('.lot\\:tabs');
    sources && toCount(sources) && sources.forEach(source => {
        let panes = [].slice.call(getChildren(source)),
            tabs = [].slice.call(getElements(targets, panes.shift())),
            input = setElement('input'), name, value;
        input.type = 'hidden';
        input.name = name = getDatum(source, 'name');
        setChildLast(source, input);
        function onClick(e) {
            let t = this,
                pane = panes[t._tabIndex],
                parent = getParent(t);
            if (!hasClass(parent, 'has:link')) {
                tabs.forEach(tab => {
                    if (tab !== t) {
                        letClass(tab, 'is:current');
                        letClass(getParent(tab), 'is:current');
                        let pane = panes[tab._tabIndex];
                        pane && letClass(pane, 'is:current');
                    }
                });
                if (hasClass(parent, 'can:toggle')) {
                    toggleClass(t, 'is:current');
                    toggleClass(parent, 'is:current');
                    if (pane) {
                        toggleClass(pane, 'is:current');
                        input.value = value = hasClass(t, 'is:current') ? getDatum(t, 'value') : null;
                    }
                } else {
                    setClass(t, 'is:current');
                    setClass(parent, 'is:current');
                    if (pane) {
                        setClass(pane, 'is:current');
                        input.value = value = getDatum(t, 'value');
                    }
                }
                pane && W._.fire.apply(pane, ['change.tab', [value, name]]);
            }
            offEventDefault(e);
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
    if (e.defaultPrevented) {
        return;
    }
    let t = this,
        key = e.key,
        keyIsAlt = e.altKey,
        keyIsCtrl = e.ctrlKey, stop;
    if (!keyIsAlt && !keyIsCtrl) {
        let any, current, next, parent, prev;
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
            stop = true;
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
            stop = true;
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
            stop = true;
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
            stop = true;
        } else if (' ' === key || 'Enter' === key) {
            if (hasClass(t, 'can:toggle')) {
                fireEvent('click', t);
                isFunction(t.focus) && t.focus();
            }
            stop = true;
        } else if ('Escape' === key) {
            if (parent = t.closest('.lot\\:tabs')) {
                isFunction(parent.focus) && parent.focus();
            }
            stop = true;
        } else if ('End' === key) {
            if (parent = t.closest('.lot\\:tabs')) {
                any = [].slice.call(getElements(targets, parent));
                if (current = any.pop()) {
                    fireEvent('click', current);
                    isFunction(current.focus) && current.focus();
                }
            }
            stop = true;
        } else if ('Home' === key) {
            if (parent = t.closest('.lot\\:tabs')) {
                if (current = getElement(targets, parent)) {
                    fireEvent('click', current);
                    isFunction(current.focus) && current.focus();
                }
            }
            stop = true;
        }
    }
    stop && (offEventDefault(e), offEventPropagation(e));
}

function onKeyDownTabs(e) {
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
        if ('ArrowDown' === key || 'ArrowRight' === key || 'Home' === key || 'PageDown' === key) {
            if (current = getElement(targets, t)) {
                fireEvent('click', current);
                isFunction(current.focus) && current.focus();
            }
            stop = true;
        } else if ('ArrowUp' === key || 'ArrowLeft' === key || 'End' === key || 'PageUp' === key) {
            any = [].slice.call(getElements(targets, t));
            if (current = any.pop()) {
                fireEvent('click', current);
                isFunction(current.focus) && current.focus();
            }
            stop = true;
        }
    }
    stop && (offEventDefault(e), offEventPropagation(e));
}

W._.on('change', onChange);