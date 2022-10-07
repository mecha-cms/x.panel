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

function onChange(init) {
    let siemas = getElements('.siema');
    siemas && toCount(siemas) && siemas.forEach(siema => {
        let slider = new Siema({
                duration: 500,
                loop: true,
                selector: siema
            });
        let interval = W.setInterval(() => slider.next(), 5000);
        onEvent('click', siema, () => W.clearInterval(interval));
    });
    // Re-calculate the Siema dimension!
    if (1 === init) {
        _.on('change.stack', () => fireEvent('resize', W));
        _.on('change.tab', () => fireEvent('resize', W));
    }
}

export default onChange;