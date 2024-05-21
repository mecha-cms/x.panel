import Siema from 'siema';

import {
    W,
    getElements
} from '@taufik-nurrohman/document';

import {
    fireEvent,
    onEvent
} from '@taufik-nurrohman/event';

import {
    toCount
} from '@taufik-nurrohman/to';

Siema.instances = [];

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
        let interval = W.setInterval(() => siema.next(), 5000);
        onEvent('mousedown', source, () => W.clearInterval(interval));
        onEvent('touchstart', source, () => W.clearInterval(interval));
        Siema.instances.push(siema);
    });
    // Re-calculate the Siema dimension!
    if (1 === init) {
        _.on('change.stack', () => fireEvent('resize', W));
        _.on('change.tab', () => fireEvent('resize', W));
    }
}

W.Siema = Siema;

export default onChange;