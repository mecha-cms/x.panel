import {
    D,
    W
} from '@taufik-nurrohman/document';

import {
    offEventDefault,
    onEvent
} from '@taufik-nurrohman/event';

import {
    hook
} from '@taufik-nurrohman/hook';

import K from '@taufik-nurrohman/key';

let map = new K(W);

onEvent('blur', W, e => map.pull());

onEvent('keydown', W, e => {
    map.push(e.key);
    let command = map.test();
    if (command) {
        let value = map.fire(command);
        if (false === value) {
            offEventDefault(e);
        } else if (null === value) {
            console.error('Unknown command:', command);
        }
    }
});

onEvent('keyup', W, e => map.pull(e.key));

const _ = {
    commands: map.commands,
    keys: map.keys
};

const {fire, hooks, off, on} = hook(_);

W.K = K;
W._ = _;

onEvent('beforeload', D, () => fire('let'));
onEvent('load', D, () => fire('get'));
onEvent('DOMContentLoaded', D, () => fire('set'));