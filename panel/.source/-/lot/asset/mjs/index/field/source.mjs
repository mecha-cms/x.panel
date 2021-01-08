import {W, getAttributes, getClasses, getDatum, getElements, setClasses} from '@taufik-nurrohman/document';
import {toCount} from '@taufik-nurrohman/to';

export function hook() {
    for (let key in TE.instances) {
        TE.instances[key].pop(); // Destroy!
        delete TE.instances[key];
    }
    let sources = getElements('.field\\:source .textarea');
    toCount(sources) && sources.forEach(source => {
        let editor = new TE(source, getDatum(source, 'state') ?? {});
    });
}
