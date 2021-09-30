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
                    while (next = getNext(parent)) {
                        if (!hasClass(next, 'not:active')) {
                            break;
                        }
                    }
                }
                current = next && getChildFirst(next);
            }
            if (current) {
                isFunction(current.focus) && current.focus();
                isFunction(current.click) && current.click();
            }
            offEventDefault(e);
            offEventPropagation(e);
        } else if ('ArrowLeft' === key || 'PageUp' === key) {
            if (parent = getParent(t)) {
                while (prev = getPrev(parent)) {
                    if (!hasClass(prev, 'not:active')) {
                        break;
                    }
                }
            }
            if (current = prev && getChildFirst(prev)) {
                isFunction(current.focus) && current.focus();
                isFunction(current.click) && current.click();
            }
            offEventDefault(e);
            offEventPropagation(e);
        } else if ('ArrowRight' === key || 'PageDown' === key) {
            if (parent = getParent(t)) {
                while (next = getNext(parent)) {
                    if (!hasClass(next, 'not:active')) {
                        break;
                    }
                }
            }
            if (current = next && getChildFirst(next)) {
                isFunction(current.focus) && current.focus();
                isFunction(current.click) && current.click();
            }
            offEventDefault(e);
            offEventPropagation(e);
        } else if ('ArrowUp' === key) {
            if (hasClass(t, 'can:toggle') && hasClass(t, 'is:current')) {
                current = t;
            } else {
                if (parent = getParent(t)) {
                    while (prev = getPrev(parent)) {
                        if (!hasClass(prev, 'not:active')) {
                            break;
                        }
                    }
                }
                current = prev && getChildFirst(prev);
            }
            if (current) {
                isFunction(current.focus) && current.focus();
                isFunction(current.click) && current.click();
            }
            offEventDefault(e);
            offEventPropagation(e);
        } else if (' ' === key || 'Enter' === key) {
            if (hasClass(t, 'can:toggle')) {
                isFunction(t.focus) && t.focus();
                isFunction(t.click) && t.click();
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
        keyIsCtrl = e.ctrlKey;
    if (keyIsAlt && keyIsCtrl) {
        let current, next, parent, prev;
        if ('PageDown' === key) {
            current = getElement('a[target^="tab:"].is\\:current', t);
            if (parent = current && getParent(current)) {
                while (next = getNext(parent)) {
                    if (!hasClass(next, 'not:active')) {
                        break;
                    }
                }
            }
            if (current = next && getChildFirst(next)) {
                isFunction(current.focus) && current.focus();
                isFunction(current.click) && current.click();
            }
            offEventDefault(e);
            offEventPropagation(e);
        } else if ('PageUp' === key) {
            current = getElement('a[target^="tab:"].is\\:current', t);
            if (parent = current && getParent(current)) {
                while (prev = getPrev(parent)) {
                    if (!hasClass(prev, 'not:active')) {
                        break;
                    }
                }
            }
            if (current = prev && getChildFirst(prev)) {
                isFunction(current.focus) && current.focus();
                isFunction(current.click) && current.click();
            }
            offEventDefault(e);
            offEventPropagation(e);
        }
    }
}

W._.on('change', onChange);