import {D, R, W, getAttribute, getDatum, getElement, getElements, getHTML, getName, getParent, hasClass, letDatum, setAttribute, setDatum, setHTML, theLocation} from '@taufik-nurrohman/document';
import {off as offEvent, on as onEvent} from '@taufik-nurrohman/event';
import {fire as fireHook, hooks as theHooks, off as offHook, on as onHook} from '@taufik-nurrohman/hook';

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

if (hasClass(R, 'can:fetch')) {
    let title = getElement('title'),
        selectors = 'body>div,body>svg',
        elements = getElements(selectors);
    f3h = new F3H(false); // Disable cache
    f3h.on('error', () => {
        fireHook('error');
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
        fireHook('let');
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
            fireHook('change');
        }
    });
    onHook('change', doMenuHook);
    onHook('change', doQueryHook);
    onHook('change', doSourceHook);
    onHook('change', doTabHook);
    onHook('let', () => {
        if (title) {
            let status = getDatum(title, 'is') || 'pull',
                value = getDatum(title, 'is-' + status);
            value && (D.title = value);
        }
    });
}

onEvent('beforeload', D, () => fireHook('let'));
onEvent('load', D, () => fireHook('get'));
onEvent('DOMContentLoaded', D, () => fireHook('set'));

doMenuHook();
doQueryHook();
doSourceHook();
doTabHook();

export default {
    f3h,
    fire: fireHook,
    hooks: theHooks,
    off: offHook,
    on: onHook
};
