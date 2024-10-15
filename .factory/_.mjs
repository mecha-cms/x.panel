import {
    offEvent,
    onEvent
} from '@taufik-nurrohman/event';

import {
    isArray,
    isFunction,
    isObject
} from '@taufik-nurrohman/is';

import {
    toCount,
    toObjectCount
} from '@taufik-nurrohman/to';

export function fireFocus(node) {
    node && isFunction(node.focus) && node.focus();
}

export function fireSelect(node) {
    node && isFunction(node.select) && node.select();
}

export function onEventOnly(event, node, then) {
    offEvent(event, node, then);
    return onEvent(event, node, then);
}

export function removeNull(object) {
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
        return 0 !== toCount(out) ? out : false;
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
    return 0 !== toObjectCount(object) ? object : false;
}