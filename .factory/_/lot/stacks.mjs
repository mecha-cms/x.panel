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
    isArray,
    isFunction,
    isObject
} from '@taufik-nurrohman/is';

import {
    toCount,
    toObjectCount,
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

function removeNull(object) {
    if (isArray(object)) {
        let out = [];
        for (let i = 0, j = toCount(object); i < j; ++i) {
            if (null === object[i]) {
                continue;
            }
            if (isArray(object[i])) {
                if (null === (object[i] = removeNull(object[i])) || 0 === object[i].length) {
                    continue;
                }
            } else if (isObject(object[i])) {
                if (null === (object[i] = removeNull(object[i])) || 0 === toObjectCount(object[i])) {
                    continue;
                }
            }
            out.push(object[i]);
        }
        return 0 !== toCount(out) ? out : null;
    }
    for (let k in object) {
        if (null === object[k]) {
            delete object[k];
            continue;
        }
        if (isArray(object[k]) || isObject(object[k])) {
            if (null === (object[k] = removeNull(object[k])) || 0 === toObjectCount(object[k])) {
                delete object[k];
            }
        }
    }
    return 0 !== toObjectCount(object) ? object : null;
}

const STACK_INPUT = 0;
const STACK_OF = 1;
const STACK_STACKS = 2;

function onChange(init) {
    let sources = getElements('.lot\\:stacks[tabindex]');
    sources && toCount(sources) && sources.forEach(source => {
        let stackCurrent,
            stacks = [].slice.call(getChildren(source)).filter(v => hasClass(v, 'lot:stack')),
            input = setElement('input'), name, target;
        input.type = 'hidden';
        input.name = name = getDatum(source, 'name');
        if (name) {
            getElements('input[name="' + name + '"]', source).forEach(v => letElement(v));
            setChildLast(source, input);
        }
        stacks.forEach((stack, index) => {
            if (!(target = getElement(targets, stack))) {
                return;
            }
            target._ = target._ || {};
            target._[STACK_INPUT] = input;
            target._[STACK_OF] = index;
            target._[STACK_STACKS] = stacks;
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
    let name = t._[STACK_INPUT].name;
    if (!hasClass(parent, 'has:link')) {
        t._[STACK_STACKS].forEach(stack => {
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
        t._[STACK_INPUT].value = value = current ? getDatum(parent, 'value') : null;
        toggleClass(self, 'has:current', current);
        let {pathname, search} = theLocation;
        let query = fromQuery(search);
        let q = fromQuery(name + '=' + value);
        query = fromStates(query, q.query || {});
        if (null === value) {
            query = removeNull(query);
        }
        theHistory.replaceState({}, "", pathname + (null !== query ? toQuery(query) : ""));
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