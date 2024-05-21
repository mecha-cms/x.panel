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

import TextEditor from '@taufik-nurrohman/text-editor';
import TextEditorHistory from '@taufik-nurrohman/text-editor.history';
import TextEditorKey from '@taufik-nurrohman/text-editor.key';
import TextEditorSource from '@taufik-nurrohman/text-editor.source';

TextEditor.instances = [];

TextEditor.state.with.push(TextEditorHistory);
TextEditor.state.with.push(TextEditorKey);
TextEditor.state.with.push(TextEditorSource);

const bounce = debounce(map => map.pull(), 1000);

function onChange(init) {
    let instance;
    while (instance = TextEditor.instances.pop()) {
        instance.detach();
    }
    let sources = getElements('.lot\\:field.type\\:source .textarea'), editor, state, type;
    sources && toCount(sources) && sources.forEach(source => {
        editor = new TextEditor(source, state = getDatum(source, 'state') ?? {});
        editor.command('pull', function () {
            return this.pull(), false;
        });
        editor.command('push', function () {
            return this.push(), false;
        });
        editor.key('Control-[', 'pull');
        editor.key('Control-]', 'push');
        editor.key('Escape', function () {
            let parent = getParent(this.self, '[tabindex]:not(.not\\:active)');
            if (parent) {
                return parent.focus({
                    // <https://developer.mozilla.org/en-US/docs/Web/API/HTMLElement/focus#focusvisible>
                    focusVisible: true
                }), false;
            }
            return true;
        });
        type = state.type || source.form.elements['data[type]'] || source.form.elements['page[type]'] || source.form.elements['file[type]'] || 'text/plain';
        if ('HTML' === type || 'text/html' === type) {
            editor.command('blocks', function () {});
            editor.command('bold', function () {});
            editor.command('code', function () {});
            editor.command('image', function () {});
            editor.command('italic', function () {});
            editor.command('link', function () {});
            editor.command('quote', function () {});
            editor.command('underline', function () {});
            editor.key('Control-Shift-"', 'quote');
            editor.key('Control-\'', 'quote');
            editor.key('Control-b', 'bold');
            editor.key('Control-e', 'code');
            editor.key('Control-h', 'blocks');
            editor.key('Control-i', 'italic');
            editor.key('Control-l', 'link');
            editor.key('Control-o', 'image');
            editor.key('Control-u', 'underline');
        } else if ('Markdown' === type || 'text/markdown' === type || 'text/x-markdown' === type) {
            // TODO
        }
        TextEditor.instances.push(editor);
    });
    if (1 === init) {
        W._.on('change', onChange);
    }
}

W.TextEditor = TextEditor;

export default onChange;