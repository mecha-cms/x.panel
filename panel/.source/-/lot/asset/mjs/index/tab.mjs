import {D, W, getChildren, getElements, getParent, getParentForm, hasClass, letClass, setClass, theHistory} from '@taufik-nurrohman/document';
import {eventPreventDefault, on} from '@taufik-nurrohman/event';
import {toCount} from '@taufik-nurrohman/to';

export function hook() {
    let sources = getElements('.lot\\:tab'),
        hasReplaceState = 'replaceState' in theHistory,
        doSetFormAction = node => {
            let href = node.href,
                form = getParentForm(node);
            form && (form.action = href);
        };
    if (toCount(sources)) {
        sources.forEach(source => {
            let panes = [].slice.call(getChildren(source)),
                buttons = getElements('a', panes.shift());
            function onClickShowTab(e) {
                let t = this;
                if (!hasClass(getParent(t), 'has:link')) {
                    if (!hasClass(t, 'not:active')) {
                        buttons.forEach(button => {
                            letClass(getParent(button), 'is:active');
                            if (panes[button._tabIndex]) {
                                letClass(panes[button._tabIndex], 'is:active');
                            }
                        });
                        setClass(getParent(t), 'is:active');
                        if (panes[t._tabIndex]) {
                            setClass(panes[t._tabIndex], 'is:active');
                        }
                        hasReplaceState && theHistory.replaceState({}, "", t.href);
                        doSetFormAction(t);
                    }
                    eventPreventDefault(e);
                }
            }
            buttons.forEach((button, index) => {
                button._tabIndex = index;
                on('click', button, onClickShowTab);
            });
        });
    }
}
