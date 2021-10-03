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
    var getParent = function getParent(node) {
        return node.parentNode || null;
    };
    var getPrev = function getPrev(node) {
        return node.previousElementSibling || null;
    };
    var hasClass = function hasClass(node, value) {
        return node.classList.contains(value);
    };
    var offEvent = function offEvent(name, node, then) {
        node.removeEventListener(name, then);
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
    const targets = 'a[href]:not(.not\\:active)';

    function onChange() {
        let links = getElements('.lot\\:links[tabindex]');
        links && toCount(links) && links.forEach(link => {
            let linkLinks = getElements(targets, link);
            linkLinks && toCount(linkLinks) && linkLinks.forEach(linkLink => {
                offEvent('keydown', linkLink, onKeyDownLink);
                onEvent('keydown', linkLink, onKeyDownLink);
            });
            offEvent('keydown', link, onKeyDownLinks);
            onEvent('keydown', link, onKeyDownLinks);
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
            current,
            parent,
            next,
            prev,
            stop;
        if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
            if (parent = getParent(t)) {
                next = getNext(parent);
                while (next && (hasClass(next, 'is:separator') || hasClass(next, 'not:active'))) {
                    next = getNext(next);
                }
                prev = getPrev(parent);
                while (prev && (hasClass(prev, 'is:separator') || hasClass(prev, 'not:active'))) {
                    prev = getPrev(prev);
                }
            }
            if ('ArrowLeft' === key) {
                if (current = prev && getChildFirst(prev)) {
                    isFunction(current.focus) && current.focus();
                }
                stop = true;
            } else if ('ArrowRight' === key) {
                if (current = next && getChildFirst(next)) {
                    isFunction(current.focus) && current.focus();
                }
                stop = true;
            } else if ('End' === key) {
                if (parent = t.closest('.lot\\:links')) {
                    any = [].slice.call(getElements(targets, parent));
                    if (current = any.pop()) {
                        isFunction(current.focus) && current.focus();
                    }
                }
                stop = true;
            } else if ('Escape' === key) {
                if (parent = t.closest('.lot\\:links')) {
                    isFunction(parent.focus) && parent.focus();
                }
            } else if ('Home' === key) {
                if (parent = t.closest('.lot\\:links')) {
                    if (current = getElement(targets, parent)) {
                        isFunction(current.focus) && current.focus();
                    }
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
            stop;
        if (t !== e.target) {
            return;
        }
        if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
            let any, current;
            if ('ArrowRight' === key || 'Home' === key) {
                if (current = getElement(targets, t)) {
                    isFunction(current.focus) && current.focus();
                }
                stop = true;
            } else if ('ArrowLeft' === key || 'End' === key) {
                any = [].slice.call(getElements(targets, t));
                if (current = any.pop()) {
                    isFunction(current.focus) && current.focus();
                }
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }
    W._.on('change', onChange);
})();