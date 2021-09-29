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
            tabs = [].slice.call(getElements('a', panes.shift())),
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
        });
        let tabCurrent = tabs.find((value, key) => 0 !== key && hasClass(getParent(value), 'is:current'));
        if (tabCurrent) {
            input.value = getDatum(tabCurrent, 'name');
        }
        onEvent('keydown', source, onKeyDown);
    });
} onChange();

function onKeyDown(e) {
    let t = this,
        key = e.key,
        keyIsAlt = e.altKey,
        keyIsCtrl = e.ctrlKey;
    if (keyIsAlt && keyIsCtrl) {
        let current, next, parent, prev;
        if ('PageDown' === key) {
            current = getElement('nav.lot\\:links a.is\\:current', t);
            parent = current && getParent(current);
            if (parent) {
                while (next = getNext(parent)) {
                    if (!hasClass(next, 'not:active')) {
                        break;
                    }
                }
            }
            current = next && getChildFirst(next);
            if (current) {
                isFunction(current.focus) && current.focus();
                isFunction(current.click) && current.click();
            }
            offEventDefault(e);
            offEventPropagation(e);
        } else if ('PageUp' === key) {
            current = getElement('nav.lot\\:links a.is\\:current', t);
            parent = current && getParent(current);
            if (parent) {
                while (prev = getPrev(parent)) {
                    if (!hasClass(prev, 'not:active')) {
                        break;
                    }
                }
            }
            current = prev && getChildFirst(prev);
            if (current) {
                isFunction(current.focus) && current.focus();
                isFunction(current.click) && current.click();
            }
            offEventDefault(e);
            offEventPropagation(e);
        }
    }
}

W._.on('change', onChange);