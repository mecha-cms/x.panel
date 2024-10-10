import Siema from 'siema';

import {
    W,
    getElements
} from '@taufik-nurrohman/document';

import {
    fireEvent,
    offEvent,
    onEvent
} from '@taufik-nurrohman/event';

import {
    toCount
} from '@taufik-nurrohman/to';

Siema.instances = [];

function onEventOnly(event, node, then) {
    offEvent(event, node, then);
    return onEvent(event, node, then);
}

function onChange(init) {
    let instance;
    while (instance = Siema.instances.pop()) {
        instance.destroy();
    }
    let sources = getElements('.siema');
    sources && toCount(sources) && sources.forEach(source => {
        let siema = new Siema({
                duration: 600,
                loop: true,
                selector: source
            });
        source._siemaInterval = W.setInterval(() => siema.next(), 5000);
        onEventOnly('mousedown', source, onMouseDownSiema);
        onEventOnly('touchstart', source, onTouchStartSiema);
        Siema.instances.push(siema);
    });
    // Re-calculate the Siema dimension!
    if (1 === init) {
        _.on('change.stack', () => fireEvent('resize', W));
        _.on('change.tab', () => fireEvent('resize', W));
    }
}

function onMouseDownSiema() {
    W.clearInterval(this._siemaInterval);
}

function onTouchStartSiema() {
    onMouseDownSiema.call(this);
}

W.Siema = Siema;

export default onChange;