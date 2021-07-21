import {
    D,
    R,
    W,
    getAttribute,
    getChildren,
    getClasses,
    getDatum,
    getElement,
    getElements,
    getHTML,
    getName,
    getNext,
    getParent,
    getParentForm,
    getPrev,
    hasClass,
    letClass,
    letDatum,
    setAttribute,
    setChildLast,
    setClass,
    setClasses,
    setDatum,
    setHTML,
    theHistory,
    theLocation,
    toggleClass
} from '@taufik-nurrohman/document';

import {
    offEvent,
    offEventDefault,
    offEventPropagation,
    onEvent
} from '@taufik-nurrohman/event';

import {
    hook
} from '@taufik-nurrohman/hook';

import {
    toCount
} from '@taufik-nurrohman/to';

import F3H from '@taufik-nurrohman/f3h';
import OP from '@taufik-nurrohman/option-picker';
import TE from '@taufik-nurrohman/text-editor';
import TP from '@taufik-nurrohman/tag-picker';

import {
    that as thatHistory
} from '@taufik-nurrohman/text-editor.history';

import {
    canKeyDown as canKeyDownSource,
    canKeyDownDent as canKeyDownDentSource,
    canKeyDownHistory as canKeyDownHistorySource,
    canKeyUp as canKeyUpSource,
    state as stateSource,
    that as thatSource
} from '@taufik-nurrohman/text-editor.source';

import {
    canKeyDown as canKeyDownSourceXML,
    canMouseDown as canMouseDownSourceXML,
    state as stateSourceXML,
    that as thatSourceXML
} from '@taufik-nurrohman/text-editor.source-x-m-l';


/* Global(s) */

const _ = {};

const {fire, hooks, off, on} = hook(_);

Object.assign(W, {F3H, OP, TE, TP, _});


/* Menu(s) */

function _hideMenus(but) {
    getElements('.lot\\:menu.is\\:enter').forEach(node => {
        if (but !== node) {
            letClass(node, 'is:enter');
            letClass(getParent(node), 'is:active');
            letClass(getPrev(node), 'is:active');
        }
    });
}

function _clickHideMenus() {
    _hideMenus(0);
}

function _clickShowMenu(e) {
    let t = this,
        current = getNext(t);
    _hideMenus(current);
    W.setTimeout(() => {
        toggleClass(t, 'is:active');
        toggleClass(getParent(t), 'is:active');
        toggleClass(current, 'is:enter');
    }, 1);
    offEventDefault(e);
    offEventPropagation(e);
}

function onChange_Menu() {
    offEvent('click', D, _clickHideMenus);
    let menuParents = getElements('.has\\:menu');
    if (menuParents && toCount(menuParents)) {
        menuParents.forEach(menuParent => {
            let menu = getElement('.lot\\:menu', menuParent),
                a = getPrev(menu);
            if (menu && a) {
                onEvent('click', a, _clickShowMenu);
            }
        });
        onEvent('click', D, _clickHideMenus);
    }
}


/* Option(s) */

function onChange_Option() {
    // Destroy!
    let value;
    for (let key in OP.instances) {
        value = OP.instances[key];
        value.pop();
        delete OP.instances[key];
    }
    let sources = getElements('.lot\\:field.type\\:option .select');
    sources && toCount(sources) && sources.forEach(source => {
        let c = getClasses(source);
        let picker = new OP(source, getDatum(source, 'state') ?? {});
        setClasses(picker.self, c);
    });
}


/* Query(s) */

function onChange_Query() {
    // Destroy!
    let value;
    for (let key in TP.instances) {
        value = TP.instances[key];
        value.pop();
        delete TP.instances[key];
    }
    let sources = getElements('.lot\\:field.type\\:query .input');
    sources && toCount(sources) && sources.forEach(source => {
        let c = getClasses(source);
        let picker = new TP(source, getDatum(source, 'state') ?? {});
        setClasses(picker.self, c);
    });
}


/* Source(s) */

Object.assign(TE.prototype, thatHistory, thatSource);

Object.assign(TE.state, stateSource, stateSourceXML);

function _onKeyDownSource(e) {
    let editor = this.editor,
        key = e.key,
        keys = {a: e.altKey, c: e.ctrlKey, s: e.shiftKey};
    if (
        canKeyDownSourceXML(key, keys, editor) &&
        canKeyDownSource(key, keys, editor) &&
        canKeyDownDentSource(key, keys, editor) &&
        canKeyDownHistorySource(key, keys, editor)
    ) {} else {
        offEventDefault(e);
    }
}

function _onMouseDownSource(e) {
    let editor = this.editor;
    canMouseDownSourceXML(editor) || offEventDefault(e);
}

function _onKeyUpSource(e) {
    let editor = this.editor,
        key = e.key,
        keys = {a: e.altKey, c: e.ctrlKey, s: e.shiftKey};
    canKeyUpSource(key, keys, editor) || offEventDefault(e);
}

function _letEditorSource(self) {
    delete self.editor;
    offEvent('keydown', self, _onKeyDownSource);
    offEvent('keyup', self, _onKeyUpSource);
    offEvent('mousedown', self, _onMouseDownSource);
    offEvent('touchstart', self, _onMouseDownSource);
}

function _setEditorSource(self, editor) {
    self.editor = editor;
    onEvent('keydown', self, _onKeyDownSource);
    onEvent('keyup', self, _onKeyUpSource);
    onEvent('mousedown', self, _onMouseDownSource);
    onEvent('touchstart', self, _onMouseDownSource);
}

function onChange_Source() {
    // Destroy!
    let value;
    for (let key in TE.instances) {
        value = TE.instances[key];
        value.pop();
        _letEditorSource(value.self);
        delete TE.instances[key];
    }
    let sources = getElements('.lot\\:field.type\\:source .textarea');
    sources && toCount(sources) && sources.forEach(source => {
        let editor = new TE(source, getDatum(source, 'state') ?? {});
        _setEditorSource(editor.self, editor);
    });
}


/* Tab(s) */

function onChange_Tab() {
    let sources = getElements('.lot\\:tabs');
    sources && toCount(sources) && sources.forEach(source => {
        let panes = [].slice.call(getChildren(source)),
            input = D.createElement('input'),
            buttons = [].slice.call(getElements('a', panes.shift()));
        input.type = 'hidden';
        input.name = getDatum(source, 'name');
        setChildLast(source, input);
        function onClick(e) {
            let t = this;
            if (!hasClass(getParent(t), 'has:link')) {
                if (!hasClass(t, 'not:active')) {
                    buttons.forEach(button => {
                        letClass(getParent(button), 'is:current');
                        if (panes[button._tabIndex]) {
                            letClass(panes[button._tabIndex], 'is:current');
                        }
                    });
                    setClass(getParent(t), 'is:current');
                    if (panes[t._tabIndex]) {
                        setClass(panes[t._tabIndex], 'is:current');
                        input.value = getDatum(t, 'name');
                    }
                }
                offEventDefault(e);
            }
        }
        buttons.forEach((button, index) => {
            button._tabIndex = index;
            onEvent('click', button, onClick);
        });
        let buttonCurrent = buttons.find((value, key) => 0 !== key && hasClass(getParent(value), 'is:current'));
        if (buttonCurrent) {
            input.value = getDatum(buttonCurrent, 'name');
        }
    });
}


/* Fetch(s) */

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
    on('change', onChange_Menu);
    on('change', onChange_Option);
    on('change', onChange_Query);
    on('change', onChange_Source);
    on('change', onChange_Tab);
    on('let', () => {
        if (title) {
            let status = getDatum(title, 'is') || 'pull',
                value = getDatum(title, 'is-' + status);
            value && (D.title = value);
        }
    });
}

hasClass(R, 'can:fetch') && _setFetchFeature();

onChange_Menu();
onChange_Option();
onChange_Query();
onChange_Source();
onChange_Tab();

onEvent('beforeload', D, () => fire('let'));
onEvent('load', D, () => fire('get'));
onEvent('DOMContentLoaded', D, () => fire('set'));
