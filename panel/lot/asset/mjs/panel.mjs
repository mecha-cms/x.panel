import {
    event,
    fireEvent,
    offEvent,
    onEvent
} from '@taufik-nurrohman/event';

import {
    fire,
    hooks,
    off,
    on
} from '@taufik-nurrohman/hook';

const doc = document;
const win = window;

import {hook as queryHook} from './panel/field/query.mjs';
import {hook as sourceHook} from './panel/field/source.mjs';

import {hook as menuHook} from './panel/menu.mjs';
import {hook as tabHook} from './panel/tab.mjs';

import './panel/fetch.mjs';

function querySelector(query, base) {
    return (base || doc).querySelector(query);
}

function querySelectorAll(query, base) {
    return (base || doc).querySelectorAll(query);
}

let root = doc.documentElement,
    f3h = null;
if (root.classList.contains('can:fetch')) {
    // Get the default F3H element(s) filter
    let f = F3H.state.is;
    // Ignore navigation link(s) that has sub-menu(s) in it
    F3H.state.is = (source, refNow) => {
        return f(source, refNow) && !source.parentNode.classList.contains('has:menu');
    };
    let selectors = 'body>div,body>svg',
        elements = querySelectorAll(selectors);
    f3h = new F3H(false);
    // Force response type as `document`
    delete F3H.state.types.CSS;
    delete F3H.state.types.JS;
    delete F3H.state.types.JSON;
    f3h.on('error', () => {
        win.location.reload();
        fire('error');
    });
    f3h.on('exit', (response, target) => {
        let title = querySelector('title');
        if (title) {
            if (target && target.nodeName && 'form' === target.nodeName.toLowerCase()) {
                title.setAttribute('data-is', 'get' === target.name ? 'search' : 'push');
            } else {
                title.removeAttribute('data-is');
            }
        }
        fire('let');
    });
    f3h.on('success', (response, target) => {
        let status = f3h.status;
        if (200 === status || 404 === status) {
            let responseElements = querySelectorAll(selectors, response),
                responseRoot = response.documentElement;
            doc.title = response.title;
            responseRoot && root.setAttribute('class', responseRoot.getAttribute('class') + ' can:fetch');
            elements.forEach((element, index) => {
                if (responseElements[index]) {
                    element.setAttribute('class', responseElements[index].getAttribute('class'));
                    element.innerHTML = responseElements[index].innerHTML;
                }
            });
            fire('change');
        }
    });
    on('change', menuHook);
    on('change', queryHook);
    on('change', sourceHook);
    on('change', tabHook);
    on('let', () => {
        let title = querySelector('title');
        let status = title.getAttribute('data-is') || 'pull',
            titleStatus = title.getAttribute('data-is-' + status);
        titleStatus && (doc.title = titleStatus);
    });
}

onEvent('beforeload', doc, () => fire('let'));
onEvent('load', doc, () => fire('get'));
onEvent('DOMContentLoaded', doc, () => fire('set'));

menuHook();
queryHook();
sourceHook();
tabHook();

export default {f3h, fire, hooks, off, on};
