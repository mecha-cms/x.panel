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
    setAttribute,
    setChildLast,
    setClass,
    setElement,
    theHistory,
    theLocation,
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
    fromQuery,
    fromStates
} from '@taufik-nurrohman/from';

import {
    isFunction
} from '@taufik-nurrohman/is';

import {
    toCount,
    toQuery
} from '@taufik-nurrohman/to';

const targets = 'a[target^="tab:"]:not(.not\\:active)';

function fireFocus(node) {
    node && isFunction(node.focus) && node.focus();
}

function onEventOnly(event, node, then) {
    offEvent(event, node, then);
    return onEvent(event, node, then);
}

function onChange(init) {
    let sources = getElements('.lot\\:tabs[tabindex]');
    sources && toCount(sources) && sources.forEach(source => {
        let panes = [].slice.call(getChildren(source)),
            tabCurrent,
            tabs = [].slice.call(getElements(targets, panes.shift())),
            input = setElement('input'), name;
        input.type = 'hidden';
        input.name = name = getDatum(source, 'name');
        name && setChildLast(source, input);
        tabs.forEach((tab, index) => {
            tab._input = input;
            tab._of = index;
            tab._panes = panes;
            tab._tabs = tabs;
            onEventOnly('click', tab, onClickTab);
            onEventOnly('keydown', tab, onKeyDownTab);
        });
        tabCurrent = tabs.find((value, key) => 0 !== key && hasClass(getParent(value), 'is:current'));
        if (tabCurrent) {
            input.value = getDatum(tabCurrent, 'value');
        }
        onEventOnly('keydown', source, onKeyDownTabs);
    });
    1 === init && W._.on('change', onChange);
}

function onClickTab(e) {
    let t = this,
        pane = t._panes[t._of],
        parent = getParent(t),
        self = getParent(parent, '.lot\\:tabs'), current, value;
    let name = t._input.name;
    if (!hasClass(parent, 'has:link')) {
        t._tabs.forEach(tab => {
            if (tab !== t) {
                letClass(getParent(tab), 'is:current');
                letClass(tab, 'is:current');
                setAttribute(tab, 'aria-selected', 'false');
                setAttribute(tab, 'tabindex', '-1');
                let pane = t._panes[tab._of];
                pane && letClass(pane, 'is:current');
            }
        });
        if (hasClass(parent, 'can:toggle')) {
            toggleClass(parent, 'is:current');
            toggleClass(t, 'is:current');
            setAttribute(t, 'aria-selected', hasClass(t, 'is:current') ? 'true' : 'false');
            setAttribute(t, 'tabindex', hasClass(t, 'is:current') ? '0' : '-1');
        } else {
            setClass(parent, 'is:current');
            setClass(t, 'is:current');
            setAttribute(t, 'aria-selected', 'true');
            setAttribute(t, 'tabindex', '0');
        }
        current = hasClass(t, 'is:current');
        if (pane) {
            t._input.value = value = current ? getDatum(t, 'value') : null;
            toggleClass(pane, 'is:current', current);
            toggleClass(self, 'has:current', current);
            let {pathname, search} = theLocation;
            let query = fromQuery(search);
            let q = fromQuery(name + '=' + value);
            if (null === value) {
                console.log('TODO: Remove query: `' + name + '`');
            }
            theHistory.replaceState({}, "", pathname + toQuery(fromStates(query, q.query || {})));
            W._.fire.apply(pane, ['change.tab', [value, name]]);
        }
        offEventDefault(e);
    }
}

function onKeyDownTab(e) {
    let t = this,
        key = e.key,
        keyIsAlt = e.altKey,
        keyIsCtrl = e.ctrlKey,
        any, current, next, parent, prev, stop;
    if (!keyIsAlt && !keyIsCtrl) {
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
                fireEvent('click', current), fireFocus(current);
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
                fireEvent('click', current), fireFocus(current);
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
                fireEvent('click', current), fireFocus(current);
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
                fireEvent('click', current), fireFocus(current);
            }
            stop = true;
        } else if (' ' === key || 'Enter' === key) {
            if (hasClass(t, 'can:toggle')) {
                fireEvent('click', t), fireFocus(t);
            }
            stop = true;
        } else if ('End' === key) {
            if (parent = getParent(t, '.lot\\:tabs[tabindex]')) {
                any = [].slice.call(getElements(targets, parent));
                if (current = any.pop()) {
                    fireEvent('click', current), fireFocus(current);
                }
            }
            stop = true;
        } else if ('Home' === key) {
            if (parent = getParent(t, '.lot\\:tabs[tabindex]')) {
                if (current = getElement(targets, parent)) {
                    fireEvent('click', current), fireFocus(current);
                }
            }
            stop = true;
        }
    }
    stop && (offEventDefault(e), offEventPropagation(e));
}

function onKeyDownTabs(e) {
    let t = this,
        key = e.key,
        keyIsAlt = e.altKey,
        keyIsCtrl = e.ctrlKey,
        keyIsShift = e.shiftKey,
        any, current, next, prev, stop;
    if (keyIsAlt && keyIsCtrl && !keyIsShift) {
        current = getElement(targets + '.is\\:current', t);
        current = current && getParent(current);
        if ('PageDown' === key) {
            next = current && getNext(current);
            if (current = next && getChildFirst(next)) {
                fireEvent('click', current), fireFocus(current);
            }
            stop = true;
        } else if ('PageUp' === key) {
            prev = current && getPrev(current);
            if (current = prev && getChildFirst(prev)) {
                fireEvent('click', current), fireFocus(current);
            }
            stop = true;
        }
    } else if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
        if (t !== e.target) {
            return;
        }
        if ('ArrowDown' === key || 'ArrowRight' === key || 'PageDown' === key) {
            if (current = getElement(targets + '.is\\:current', t)) {
                fireEvent('click', current), fireFocus(current);
            }
            stop = true;
        } else if ('ArrowUp' === key || 'ArrowLeft' === key || 'PageUp' === key) {
            if (current = getElement(targets + '.is\\:current', t)) {
                fireEvent('click', current), fireFocus(current);
            }
            stop = true;
        } else if ('Home' === key) {
            if (current = getElement(targets, t)) {
                fireEvent('click', current), fireFocus(current);
            }
            stop = true;
        } else if ('End' === key) {
            any = [].slice.call(getElements(targets, t));
            if (current = any.pop()) {
                fireEvent('click', current), fireFocus(current);
            }
            stop = true;
        }
    }
    stop && (offEventDefault(e), offEventPropagation(e));
}

export default onChange;