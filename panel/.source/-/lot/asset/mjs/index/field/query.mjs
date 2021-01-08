import {W, getAttributes, getClasses, getDatum, getElements, setClasses} from '@taufik-nurrohman/document';
import {toCount} from '@taufik-nurrohman/to';

export function hook() {
    for (let key in TP.instances) {
        TP.instances[key].pop(); // Destroy!
        delete TP.instances[key];
    }
    let sources = getElements('.field\\:query .input');
    toCount(sources) && sources.forEach(source => {
        let c = getClasses(source);
        let picker = new TP(source, getDatum(source, 'state') ?? {});
        setClasses(picker.self, c);
    });
}
