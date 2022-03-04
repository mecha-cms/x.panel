(function() {
    'use strict';
    var isFunction = function isFunction(x) {
        return 'function' === typeof x;
    };
    var toCount = function toCount(x) {
        return x.length;
    };
    var D = document;
    var W = window;
    var getChildFirst = function getChildFirst(parent) {
        return parent.firstElementChild || null;
    };
    var getElement = function getElement(query, scope) {
        return (scope || D).querySelector(query);
    };
    var getElements = function getElements(query, scope) {
        return (scope || D).querySelectorAll(query);
    };
    var getNext = function getNext(node) {
        return node.nextElementSibling || null;
    };
    var getParent = function getParent(node, query) {
        if (query) {
            return node.closest(query) || null;
        }
        return node.parentNode || null;
    };
    var getPrev = function getPrev(node) {
        return node.previousElementSibling || null;
    };
    var hasClass = function hasClass(node, value) {
        return node.classList.contains(value);
    };
    var offEventDefault = function offEventDefault(e) {
        return e && e.preventDefault();
    };
    var offEventPropagation = function offEventPropagation(e) {
        return e && e.stopPropagation();
    };
    var onEvent = function onEvent(name, node, then, options) {
        if (options === void 0) {
            options = false;
        }
        node.addEventListener(name, then, options);
    };
    const targets = ':scope>ul>li>a[href]:not(.not\\:active)';

    function fireFocus(node) {
        node && isFunction(node.focus) && node.focus();
    }

    function onChange() {
        let sources = getElements('.lot\\:links[tabindex]');
        sources && toCount(sources) && sources.forEach(source => {
            let links = getElements(targets, source);
            links && toCount(links) && links.forEach(link => {
                onEvent('keydown', link, onKeyDownLink);
            });
            onEvent('keydown', source, onKeyDownLinks);
        });
    }
    onChange();

    function onKeyDownLink(e) {
        if (e.defaultPrevented) {
            return;
        }
        let t = this,
            key = e.key,
            keyIsAlt = e.altKey,
            keyIsCtrl = e.ctrlKey,
            keyIsShift = e.shiftKey,
            any,
            parent,
            next,
            prev,
            stop;
        if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
            if (parent = getParent(t)) {
                next = getNext(parent);
                while (next && (hasClass(next, 'as:separator') || hasClass(next, 'not:active'))) {
                    next = getNext(next);
                }
                prev = getPrev(parent);
                while (prev && (hasClass(prev, 'as:separator') || hasClass(prev, 'not:active'))) {
                    prev = getPrev(prev);
                }
            }
            if ('ArrowLeft' === key) {
                fireFocus(prev && getChildFirst(prev));
                stop = true;
            } else if ('ArrowRight' === key) {
                fireFocus(next && getChildFirst(next));
                stop = true;
            } else if ('End' === key) {
                if (parent = getParent(t, '.lot\\:links[tabindex]')) {
                    any = [].slice.call(getElements(targets, parent));
                    fireFocus(any.pop());
                }
                stop = true;
            } else if ('Home' === key) {
                if (parent = getParent(t, '.lot\\:links[tabindex]')) {
                    fireFocus(getElement(targets, parent));
                }
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }

    function onKeyDownLinks(e) {
        if (e.defaultPrevented) {
            return;
        }
        let t = this,
            key = e.key,
            keyIsAlt = e.altKey,
            keyIsCtrl = e.ctrlKey,
            keyIsShift = e.shiftKey,
            any,
            stop;
        if (t !== e.target) {
            return;
        }
        if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
            if ('ArrowRight' === key || 'Home' === key) {
                fireFocus(getElement(targets, t));
                stop = true;
            } else if ('ArrowLeft' === key || 'End' === key) {
                any = [].slice.call(getElements(targets, t));
                fireFocus(any.pop());
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }
    W._.on('change', onChange);
})();