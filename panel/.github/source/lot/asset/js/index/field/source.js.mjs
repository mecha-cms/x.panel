import {
    W,
    getDatum,
    getElements
} from '@taufik-nurrohman/document';

import {
    offEvent,
    offEventDefault,
    onEvent
} from '@taufik-nurrohman/event';

import {
    fromStates
} from '@taufik-nurrohman/from';

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

['alert', 'confirm', 'prompt'].forEach(type => {
    W._[type] && (TE.state.source[type] = W._[type]);
});

// Be sure to remove the default source type
delete TE.state.source.type;

function _onKeyDownSource(e) {
    let $ = this.$,
        type = $.state.source.type,
        key = e.key,
        keys = {a: e.altKey, c: e.ctrlKey, s: e.shiftKey};
    if ('CSS' === type) {
        // TODO
        // if () {} else {
        //     offEventDefault(e);
        // }
        // return;
    }
    if ('HTML' === type) {
        if (
            canKeyDownSourceHTML(key, keys, $) &&
            canKeyDownSourceXML(key, keys, $) &&
            canKeyDownSource(key, keys, $) &&
            canKeyDownDentSource(key, keys, $) &&
            canKeyDownEnterSource(key, keys, $) &&
            canKeyDownHistorySource(key, keys, $) &&
            canKeyDownMoveSource(key, keys, $)
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
    if ('PHP' === type) {
        // TODO
        // if () {} else {
        //     offEventDefault(e);
        // }
        // return;
    }
    if ('XML' === type) {
        if (
            canKeyDownSourceXML(key, keys, $) &&
            canKeyDownSource(key, keys, $) &&
            canKeyDownDentSource(key, keys, $) &&
            canKeyDownEnterSource(key, keys, $) &&
            canKeyDownHistorySource(key, keys, $) &&
            canKeyDownMoveSource(key, keys, $)
        ) {} else {
            offEventDefault(e);
        }
        return;
    }
    // Default
    if (
        canKeyDownSource(key, keys, $) &&
        canKeyDownDentSource(key, keys, $) &&
        canKeyDownEnterSource(key, keys, $) &&
        canKeyDownHistorySource(key, keys, $) &&
        canKeyDownMoveSource(key, keys, $)
    ) {} else {
        offEventDefault(e);
    }
}

function _onMouseDownSource(e) {
    let $ = this.$,
        key = e.key,
        keys = {a: e.altKey, c: e.ctrlKey, s: e.shiftKey};
    canMouseDownSourceXML(key, keys, $) || offEventDefault(e);
}

function _onKeyUpSource(e) {
    let $ = this.$,
        key = e.key,
        keys = {a: e.altKey, c: e.ctrlKey, s: e.shiftKey};
    canKeyUpSource(key, keys, $) || offEventDefault(e);
}

function _letEditorSource(self) {
    offEvent('keydown', self, _onKeyDownSource);
    offEvent('keyup', self, _onKeyUpSource);
    offEvent('mousedown', self, _onMouseDownSource);
    offEvent('touchstart', self, _onMouseDownSource);
}

function _setEditorSource(self) {
    onEvent('keydown', self, _onKeyDownSource);
    onEvent('keyup', self, _onKeyUpSource);
    onEvent('mousedown', self, _onMouseDownSource);
    onEvent('touchstart', self, _onMouseDownSource);
    self.$.record();
}

function onChange() {
    // Destroy!
    let $;
    for (let key in TE.instances) {
        $ = TE.instances[key];
        $.loss().pop();
        delete $.self.$;
        delete TE.instances[key];
        _letEditorSource($.self);
    }
    let sources = getElements('.lot\\:field.type\\:source .textarea');
    sources && toCount(sources) && sources.forEach(source => {
        source.$ = new TE(source, getDatum(source, 'state') ?? {});
        _setEditorSource(source);
    });
} onChange();

W._.on('change', onChange);

W.TE = TE;