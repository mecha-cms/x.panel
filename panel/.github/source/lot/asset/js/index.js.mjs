import {
    D,
    R,
    W,
    getAttribute,
    getDatum,
    getElement,
    getElements,
    getHTML,
    getName,
    getParent,
    hasClass,
    letDatum,
    setAttribute,
    setDatum,
    setHTML,
    theLocation
} from '@taufik-nurrohman/document';

import {
    onEvent
} from '@taufik-nurrohman/event';

import {
    hook
} from '@taufik-nurrohman/hook';

import F3H from '@taufik-nurrohman/f3h';

const _ = {};

function promisify(type, lot) {
    return new Promise((resolve, reject) => {
        let r = W[type].apply(W, lot);
        return r ? resolve(r) : reject(r);
    });
}

// Prepare for <https://developers.google.com/web/updates/2017/03/dialogs-policy>
['alert', 'confirm', 'prompt'].forEach(type => {
    _[type] = (...lot) => promisify(type, lot);
});

const {fire, hooks, off, on} = hook(_);

Object.assign(W, {F3H, _});

// Get default F3H element(s) filter
let f = F3H.state.is;

// Ignore navigation link(s) that has sub-menu(s) in it
F3H.state.is = (source, ref) => {
    return f(source, ref) && !hasClass(getParent(source), 'has:menu');
};

// Force response type as `document`
delete F3H.state.types.CSS;
delete F3H.state.types.JS;
delete F3H.state.types.JSON;

let f3h = null;

function _setFetchFeature() {
    let title = getElement('title'),
        selectors = 'body>div,body>svg,body>template',
        elements = getElements(selectors);
    f3h = new F3H(false); // Disable cache
    f3h.on('error', () => {
        fire('error');
        theLocation.reload();
    });
    f3h.on('exit', (response, node) => {
        if (title) {
            if (node && 'form' === getName(node)) {
                setDatum(title, 'is', 'get' === node.name ? 'search' : 'push');
            } else {
                letDatum(title, 'is');
            }
        }
        fire('let');
    });
    f3h.on('success', (response, node) => {
        let status = f3h.status;
        if (200 === status || 404 === status) {
            let responseElements = getElements(selectors, response),
                responseRoot = response.documentElement;
            D.title = response.title;
            if (responseRoot) {
                setAttribute(R, 'class', getAttribute(responseRoot, 'class') + ' can:fetch');
            }
            elements.forEach((element, index) => {
                if (responseElements[index]) {
                    setAttribute(element, 'class', getAttribute(responseElements[index], 'class'));
                    setHTML(element, getHTML(responseElements[index]));
                }
            });
            fire('change');
        }
    });
    on('let', () => {
        if (title) {
            let status = getDatum(title, 'is') || 'pull',
                value = getDatum(title, 'is-' + status);
            value && (D.title = value);
        }
    });
}

hasClass(R, 'can:fetch') && _setFetchFeature();

onEvent('beforeload', D, () => fire('let'));
onEvent('load', D, () => fire('get'));
onEvent('DOMContentLoaded', D, () => fire('set'));