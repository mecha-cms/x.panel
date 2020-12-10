import '@taufik-nurrohman/tag-picker';

const doc = document;
const win = window;

export function hook() {
    for (let k in TP.instances) {
        TP.instances[k].pop(); // Destroy!
        delete TP.instances[k];
    }
    let query = doc.querySelectorAll('.field\\:query .input');
    query.length && query.forEach($ => {
        let c = $.className;
        let $$ = new TP($, JSON.parse($.getAttribute('data-state') || '{}'));
        $$.view.className += ' ' + c;
    });
}
