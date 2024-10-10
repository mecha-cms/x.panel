import {
    W,
    getAttribute,
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

const targets = 'a[target^="stack:"]:not(.not\\:active)';

function fireFocus(node) {
    node && isFunction(node.focus) && node.focus();
}

function onEventOnly(event, node, then) {
    offEvent(event, node, then);
    return onEvent(event, node, then);
}

function onChange(init) {
    let sources = getElements('.lot\\:stacks[tabindex]');
    sources && toCount(sources) && sources.forEach(source => {
        let stackCurrent,
            stacks = [].slice.call(getChildren(source)).filter(v => hasClass(v, 'lot:stack')),
            input = setElement('input'), name;
        input.type = 'hidden';
        input.name = name = getDatum(source, 'name');
        name && setChildLast(source, input);
        stacks.forEach(stack => {
            let target = getElement(targets, stack);
            target._input = input;
            target._stacks = stacks;
            onEventOnly('click', target, onClickStack);
            onEventOnly('keydown', target, onKeyDownStack);
        });
        stackCurrent = stacks.find((value, key) => 0 !== key && hasClass(value, 'is:current'));
        if (stackCurrent) {
            input.value = getDatum(stackCurrent, 'value');
        }
        onEventOnly('keydown', source, onKeyDownStacks);
    });
    1 === init && W._.on('change', onChange);
}

function onClickStack(e) {
    let t = this,
        parent = getParent(getParent(t)),
        self = getParent(parent, '.lot\\:stacks'), current, value;
    let name = t._input.name;
    if (!hasClass(parent, 'has:link')) {
        t._stacks.forEach(stack => {
            if (stack !== parent) {
                letClass(current = getElement('a[target^="stack:"]', stack), 'is:current');
                letClass(stack, 'is:current');
                setAttribute(current, 'aria-expanded', 'false');
            }
        });
        if (hasClass(parent, 'can:toggle')) {
            setAttribute(t, 'aria-expanded', getAttribute(t, 'aria-expanded') ? 'false' : 'true');
            toggleClass(parent, 'is:current');
            toggleClass(t, 'is:current');
        } else {
            setAttribute(t, 'aria-expanded', 'true');
            setClass(parent, 'is:current');
            setClass(t, 'is:current');
        }
        current = hasClass(t, 'is:current');
        t._input.value = value = current ? getDatum(parent, 'value') : null;
        toggleClass(self, 'has:current', current);
        let {pathname, search} = theLocation;
        let query = fromQuery(search);
        let q = fromQuery(name + '=' + value);
        if (null === value) {
            console.log('TODO: Remove query: `' + name + '`');
        }
        theHistory.replaceState({}, "", pathname + toQuery(fromStates(query, q.query || {})));
        W._.fire.apply(parent, ['change.stack', [value, name]]);
        offEventDefault(e);
    }
}

function onKeyDownStack(e) {
    let t = this,
        key = e.key,
        keyIsAlt = e.altKey,
        keyIsCtrl = e.ctrlKey,
        any, current, next, parent, prev, stop;
    if (!keyIsAlt && !keyIsCtrl) {
        if ('ArrowDown' === key || 'ArrowRight' === key || 'PageDown' === key) {
            if ('ArrowRight' === key) {
                // TODO
            }
            if (parent = getParent(getParent(t))) {
                next = getNext(parent);
                while (next && hasClass(next, 'not:active')) {
                    next = getNext(next);
                }
            }
            if (current = next && getChildFirst(next)) {
                if ('ArrowRight' !== key || !hasClass(getParent(current), 'can:toggle')) {
                    fireEvent('click', getChildFirst(current));
                }
                fireFocus(getChildFirst(current));
            }
            stop = true;
        } else if ('ArrowUp' === key || 'ArrowLeft' === key || 'PageUp' === key) {
            if ('ArrowLeft' === key) {
                // TODO
            }
            if (parent = getParent(getParent(t))) {
                prev = getPrev(parent);
                while (prev && hasClass(prev, 'not:active')) {
                    prev = getPrev(prev);
                }
            }
            if (current = prev && getChildFirst(prev)) {
                if ('ArrowLeft' !== key || !hasClass(getParent(current), 'can:toggle')) {
                    fireEvent('click', getChildFirst(current));
                }
                fireFocus(getChildFirst(current));
            }
            stop = true;
        } else if (' ' === key || 'Enter' === key) {
            if (hasClass(getParent(getParent(t)), 'can:toggle')) {
                fireEvent('click', t), fireFocus(t);
            }
            stop = true;
        } else if ('End' === key) {
            if (parent = getParent(t, '.lot\\:stacks[tabindex]')) {
                any = [].slice.call(getElements(targets, parent));
                if (current = any.pop()) {
                    fireEvent('click', current), fireFocus(current);
                }
            }
            stop = true;
        } else if ('Home' === key) {
            if (parent = getParent(t, '.lot\\:stacks[tabindex]')) {
                if (current = getElement(targets, parent)) {
                    fireEvent('click', current), fireFocus(current);
                }
            }
            stop = true;
        }
    }
    stop && (offEventDefault(e), offEventPropagation(e));
}

function onKeyDownStacks(e) {
    let t = this,
        key = e.key,
        keyIsAlt = e.altKey,
        keyIsCtrl = e.ctrlKey,
        keyIsShift = e.shiftKey,
        any, current, next, prev, stop;
    if (keyIsAlt && keyIsCtrl && !keyIsShift) {
        current = getElement(targets + '.is\\:current', t);
        current = current && getParent(getParent(current));
        if ('PageDown' === key) {
            next = current && getNext(current);
            if (current = next && getElement(targets, next)) {
                fireEvent('click', current), fireFocus(current);
            }
            stop = true;
        } else if ('PageUp' === key) {
            prev = current && getPrev(current);
            if (current = prev && getElement(targets, prev)) {
                fireEvent('click', current), fireFocus(current);
            }
            stop = true;
        }
    } else if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
        if (t !== e.target) {
            return;
        }
        if ('ArrowDown' === key || 'ArrowRight' === key || 'Home' === key || 'PageDown' === key) {
            if (current = getElement(targets, t)) {
                fireEvent('click', current), fireFocus(current);
            }
            stop = true;
        } else if ('ArrowUp' === key || 'ArrowLeft' === key || 'End' === key || 'PageUp' === key) {
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