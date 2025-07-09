import {
    fireFocus,
    onEventOnly,
    removeNull
} from '../../_.mjs';

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
    letElement,
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
    offEventDefault,
    offEventPropagation
} from '@taufik-nurrohman/event';

import {
    fromQuery,
    fromStates
} from '@taufik-nurrohman/from';

import {
    toCount,
    toQuery
} from '@taufik-nurrohman/to';

const targets = 'a[target^="tab:"]:not(.not\\:active)';

const TAB_INPUT = 0;
const TAB_OF = 1;
const TAB_PANES = 2;
const TAB_TABS = 3;

function onChange(init) {
    let sources = getElements('.lot\\:tabs[tabindex]');
    sources && toCount(sources) && sources.forEach(source => {
        let panes = [].slice.call(getChildren(source)),
            tabCurrent,
            tabs = [].slice.call(getElements(targets, panes.shift())),
            input = setElement('input'), name;
        input.type = 'hidden';
        input.name = name = getDatum(source, 'name');
        if (name) {
            getElements('input[name="' + name + '"]', source).forEach(v => letElement(v));
            setChildLast(source, input);
        }
        tabs.forEach((tab, index) => {
            tab._ = tab._ || {};
            tab._[TAB_INPUT] = input;
            tab._[TAB_OF] = index;
            tab._[TAB_PANES] = panes;
            tab._[TAB_TABS] = tabs;
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
        pane = t._[TAB_PANES][t._[TAB_OF]],
        parent = getParent(t),
        self = getParent(parent, '.lot\\:tabs'), current, value;
    let name = t._[TAB_INPUT].name;
    if (!hasClass(parent, 'has:link')) {
        t._[TAB_TABS].forEach(tab => {
            if (tab !== t) {
                letClass(getParent(tab), 'is:current');
                letClass(tab, 'is:current');
                setAttribute(tab, 'aria-selected', 'false');
                setAttribute(tab, 'tabindex', '-1');
                let pane = t._[TAB_PANES][tab._[TAB_OF]];
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
            t._[TAB_INPUT].value = value = current ? getDatum(t, 'value') : null;
            toggleClass(pane, 'is:current', current);
            toggleClass(self, 'has:current', current);
            let {pathname, search} = theLocation;
            let query = fromQuery(search);
            let q = fromQuery(name + '=' + value);
            query = fromStates(query, q.query || {});
            if (null === value) {
                query = removeNull(query);
            }
            theHistory.replaceState({}, "", pathname + (false !== query ? toQuery(query) : ""));
            W._.fire('change.tab', [value, name], pane);
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