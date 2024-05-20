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
    let siemas = getElements('.siema');
    siemas && toCount(siemas) && siemas.forEach(siema => {
        let slider = new Siema({
                duration: 600,
                loop: true,
                selector: siema
            });
        let interval = W.setInterval(() => slider.next(), 5000);
        onEvent('mousedown', siema, () => W.clearInterval(interval));
        onEvent('touchstart', siema, () => W.clearInterval(interval));
        Siema.instances.push(slider);
    });
    // Re-calculate the Siema dimension!
    if (1 === init) {
        _.on('change.stack', () => fireEvent('resize', W));
        _.on('change.tab', () => fireEvent('resize', W));
    }
}

W.Siema = Siema;

export default onChange;