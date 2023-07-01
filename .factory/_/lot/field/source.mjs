import {
    W,
    getDatum,
    getElements,
    getParent
} from '@taufik-nurrohman/document';

import {
    offEvent,
    offEventDefault,
    offEventPropagation,
    onEvent
} from '@taufik-nurrohman/event';

import {
    fromStates
} from '@taufik-nurrohman/from';

import {
    debounce
} from '@taufik-nurrohman/tick';

import {
    toCount
} from '@taufik-nurrohman/to';

import TE from '@taufik-nurrohman/text-editor';

import {
    that as thatHistory
} from '@taufik-nurrohman/text-editor.history';

import {
    canKeyDown as canKeyDownSource,
    canKeyDownDent as canKeyDownDentSource,
    canKeyDownEnter as canKeyDownEnterSource,
    canKeyDownHistory as canKeyDownHistorySource,
    canKeyDownMove as canKeyDownMoveSource,
    canKeyUp as canKeyUpSource,
    state as stateSource,
    that as thatSource
} from '@taufik-nurrohman/text-editor.source';

import {
    canKeyDown as canKeyDownSourceHTML,
    commands as commandsSourceHTML,
    state as stateSourceHTML
} from '@taufik-nurrohman/text-editor.source-h-t-m-l';

import {
    canKeyDown as canKeyDownSourceXML,
    canMouseDown as canMouseDownSourceXML,
    state as stateSourceXML,
    that as thatSourceXML
} from '@taufik-nurrohman/text-editor.source-x-m-l';

Object.assign(TE.prototype, thatHistory, thatSource);

TE.state = fromStates({}, TE.state, stateSource, stateSourceXML, stateSourceHTML);

// Be sure to remove the default source type
delete TE.state.source.type;

const bounce = debounce(map => map.pull(), 1000);

function _onBlurSource(e) {
    this.K.pull();
}

function _onInputSource(e) {
    this.K.pull();
}

function _onKeyDownSource(e) {
    let editor = this.TE,
        map = this.K,
        key = e.key,
        type = editor.state.source.type,
        command, value;
    offEventPropagation(e);
    map.push(key);
    if (command = map.command()) {
        value = map.fire(command);
        if (false === value) {
            offEventDefault(e);
        } else if (null === value) {
            console.error('Unknown command:', command);
        }
    } else {
        if ('CSS' === type) {
            // TODO
            // if () {} else {
            //     offEventDefault(e);
            // }
            // return;
        }
        if ('HTML' === type) {
            if (
                canKeyDownSourceHTML(map, editor) &&
                canKeyDownSourceXML(map, editor) &&
                canKeyDownSource(map, editor) &&
                canKeyDownDentSource(map, editor) &&
                canKeyDownEnterSource(map, editor) &&
                canKeyDownHistorySource(map, editor) &&
                canKeyDownMoveSource(map, editor)
            ) {} else {
                offEventDefault(e);
            }
            return;
        }
        if ('JavaScript' === type) {
            // TODO
            // if () {} else {
            //     offEventDefault(e);
            // }
            // return;
        }
        if ('Markdown' === type) {
            // TODO
            // if () {} else {
            //     offEventDefault(e);
            // }
            // return;
        }
        if ('PHP' === type) {
            // TODO
            // if () {} else {
            //     offEventDefault(e);
            // }
            // return;
        }
        if ('XML' === type) {
            if (
                canKeyDownSourceXML(map, editor) &&
                canKeyDownSource(map, editor) &&
                canKeyDownDentSource(map, editor) &&
                canKeyDownEnterSource(map, editor) &&
                canKeyDownHistorySource(map, editor) &&
                canKeyDownMoveSource(map, editor)
            ) {} else {
                offEventDefault(e);
            }
            return;
        }
        // Default
        if (
            canKeyDownSource(map, editor) &&
            canKeyDownDentSource(map, editor) &&
            canKeyDownEnterSource(map, editor) &&
            canKeyDownHistorySource(map, editor) &&
            canKeyDownMoveSource(map, editor)
        ) {} else {
            offEventDefault(e);
        }
    }
    bounce(map);
}

function _onKeyUpSource(e) {
    let editor = this.TE,
        map = this.K,
        key = e.key;
    canKeyUpSource(map, editor) || offEventDefault(e);
    map.pull(key);
}

function _onMouseDownSource(e) {
    let editor = this.TE,
        map = this.K;
    canMouseDownSourceXML(map, editor) || offEventDefault(e);
}

function _letEditorSource(self) {
    offEvent('blur', self, _onBlurSource);
    offEvent('input', self, _onInputSource);
    offEvent('keydown', self, _onKeyDownSource);
    offEvent('keyup', self, _onKeyUpSource);
    offEvent('mousedown', self, _onMouseDownSource);
    offEvent('touchstart', self, _onMouseDownSource);
}

function _setEditorSource(self) {
    onEvent('blur', self, _onBlurSource);
    onEvent('input', self, _onInputSource);
    onEvent('keydown', self, _onKeyDownSource);
    onEvent('keyup', self, _onKeyUpSource);
    onEvent('mousedown', self, _onMouseDownSource);
    onEvent('touchstart', self, _onMouseDownSource);
    self.TE.record();
}

function onChange(init) {
    // Destroy!
    let $;
    for (let key in TE.instances) {
        $ = TE.instances[key];
        $.loss().pop();
        delete $.self.K;
        delete TE.instances[key];
        _letEditorSource($.self);
    }
    let sources = getElements('.lot\\:field.type\\:source .textarea'), editor, map, state, type;
    sources && toCount(sources) && sources.forEach(source => {
        editor = new TE(source, getDatum(source, 'state') ?? {});
        state = editor.state;
        type = state.source.type;
        // Get it from `window` context as this `K` object already defined in `./.factory/index.js.mjs` globally
        map = new W.K(editor);
        map.keys['Escape'] = function () {
            let parent = getParent(this.source, '[tabindex]:not(.not\\:active)');
            if (parent) {
                return parent.focus(), false;
            }
            return true;
        };
        if ('HTML' === type) {
            map.commands = commandsSourceHTML;
            map.keys['Control-Shift-"'] = 'quote';
            map.keys['Control-\''] = 'quote';
            map.keys['Control-b'] = 'bold';
            map.keys['Control-e'] = 'code';
            map.keys['Control-h'] = 'blocks';
            map.keys['Control-i'] = 'italic';
            map.keys['Control-l'] = 'link';
            map.keys['Control-o'] = 'image';
            map.keys['Control-u'] = 'underline';
        } else if ('Markdown' === type) {
            // TODO
        }
        state.commands = map.commands;
        state.keys = map.keys;
        source.K = map;
        _setEditorSource(source);
    });
    if (1 === init) {
        W._.on('change', onChange);
        ['alert', 'confirm', 'prompt'].forEach(type => {
            W._.dialog[type] && (TE.state.source[type] = W._.dialog[type]);
        });
    }
}

W.TE = TE;

export default onChange;