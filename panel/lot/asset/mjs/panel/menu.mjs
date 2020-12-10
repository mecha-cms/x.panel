import {
    offEvent,
    onEvent
} from '@taufik-nurrohman/event';

const doc = document;
const win = window;

function doHide(but) {
    doc.querySelectorAll('.lot\\:menu.is\\:enter').forEach($$ => {
        if ($$ !== but) {
            $$.classList.remove('is:enter');
            $$.parentNode.classList.remove('is:active');
            $$.previousElementSibling.classList.remove('is:active');
        }
    });
}

function onClickHide() {
    doHide(0);
}

function onClickShow(e) {
    let t = this,
        menu = t.nextElementSibling;
    doHide(menu);
    setTimeout(() => {
        t.classList.toggle('is:active');
        t.parentNode.classList.toggle('is:active');
        menu.classList.toggle('is:enter');
    }, 1);
    e.preventDefault();
    e.stopPropagation();
}

export function hook() {
    offEvent('click', doc, onClickHide);
    let dropdowns = doc.querySelectorAll('.has\\:menu');
    if (dropdowns.length) {
        dropdowns.forEach($ => {
            let menu = $.querySelector('.lot\\:menu');
            if (menu && menu.previousElementSibling) {
                onEvent('click', menu.previousElementSibling, onClickShow);
            }
        });
        onEvent('click', doc, onClickHide);
    }
}
