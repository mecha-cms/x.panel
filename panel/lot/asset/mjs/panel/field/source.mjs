import '@taufik-nurrohman/text-editor';

import '@taufik-nurrohman/text-editor/text-editor/history.js';
import '@taufik-nurrohman/text-editor/text-editor/source.js';

const doc = document;
const win = window;

export function hook() {
    for (let k in TE.instances) {
        TE.instances[k].pop(); // Destroy!
        delete TE.instances[k];
    }
    let source = doc.querySelectorAll('.field\\:source .textarea');
    source.length && source.forEach($ => {
        let $$ = new TE($, JSON.parse($.getAttribute('data-state') || '{}'));
    });
}
