import {D, R, W, getAttribute, getDatum, getElement, getElements, getHTML, getName, getParent, hasClass, letDatum, setAttribute, setDatum, setHTML, theLocation} from '@taufik-nurrohman/document';
import {off as offEvent, on as onEvent} from '@taufik-nurrohman/event';
import {context as contextHook} from '@taufik-nurrohman/hook';

import {hook as doQueryHook} from './index/field/query.mjs';
import {hook as doSourceHook} from './index/field/source.mjs';

import {hook as doMenuHook} from './index/menu.mjs';
import {hook as doTabHook} from './index/tab.mjs';

// Get the default F3H element(s) filter
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

let {fire, hooks, off, on} = contextHook({});

if (hasClass(R, 'can:fetch')) {
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
    on('change', doMenuHook);
    on('change', doQueryHook);
    on('change', doSourceHook);
    on('change', doTabHook);
    on('let', () => {
        if (title) {
            let status = getDatum(title, 'is') || 'pull',
                value = getDatum(title, 'is-' + status);
            value && (D.title = value);
        }
    });
}

onEvent('beforeload', D, () => fire('let'));
onEvent('load', D, () => fire('get'));
onEvent('DOMContentLoaded', D, () => fire('set'));

doMenuHook();
doQueryHook();
doSourceHook();
doTabHook();

export default {
    fire,
    hooks,
    off,
    on
};
