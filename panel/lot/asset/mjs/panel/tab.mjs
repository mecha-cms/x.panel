import {
    onEvent
} from '@taufik-nurrohman/event';

const doc = document;
const win = window;

export function hook() {
    let tabs = doc.querySelectorAll('.lot\\:tab'),
        replaceState = 'replaceState' in win.history,
        setAction = $ => {
            let href = $.href;
            while ($ && 'form' !== $.nodeName.toLowerCase()) {
                $ = $.parentNode;
            }
            $ && 'form' === $.nodeName.toLowerCase() && ($.action = href);
        };
    if (tabs.length) {
        tabs.forEach($ => {
            let panes = [].slice.call($.children),
                buttons = panes.shift().querySelectorAll('a');
            function onClick(e) {
                let t = this;
                if (!t.parentNode.classList.contains('has:link')) {
                    if (!t.classList.contains('not:active')) {
                        buttons.forEach($$$ => {
                            $$$.parentNode.classList.remove('is:active');
                            panes[$$$._index] && panes[$$$._index].classList.remove('is:active');
                        });
                        t.parentNode.classList.add('is:active');
                        panes[t._index] && panes[t._index].classList.add('is:active');
                        replaceState && win.history.replaceState({}, "", t.href);
                        setAction(t);
                    }
                    e.preventDefault();
                }
            }
            buttons.forEach(($$, i) => {
                $$._index = i;
                onEvent('click', $$, onClick);
            });
        });
    }
}
