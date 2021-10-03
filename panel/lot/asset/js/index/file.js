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
    const targets = ':scope>.lot\\:file[tabindex]:not(.not\\:active),:scope>.lot\\:folder[tabindex]:not(.not\\:active)';

    function fireFocus(node) {
        node && isFunction(node.focus) && node.focus();
    }

    function onChange() {
        let sources = getElements('.lot\\:files[tabindex],.lot\\:folders[tabindex]');
        sources && toCount(sources) && sources.forEach(source => {
            let files = getElements(targets, source);
            files.forEach(file => {
                onEvent('keydown', file, onKeyDownFile);
            });
            onEvent('keydown', source, onKeyDownFiles);
        });
    }
    onChange();

    function onKeyDownFile(e) {
        if (e.defaultPrevented) {
            return;
        }
        let t = this,
            key = e.key,
            any,
            next,
            prev,
            stop;
        if (t !== e.target) {
            return;
        }
        next = getNext(t);
        while (next && hasClass(next, 'not:active')) {
            next = getNext(next);
        }
        prev = getPrev(t);
        while (prev && hasClass(prev, 'not:active')) {
            prev = getPrev(prev);
        }
        if ('ArrowDown' === key) {
            fireFocus(next);
            stop = true;
        } else if ('ArrowUp' === key) {
            fireFocus(prev);
            stop = true;
        } else if ('End' === key) {
            any = [].slice.call(getElements(targets, getParent(t)));
            fireFocus(any.pop());
            stop = true;
        } else if ('Escape' === key) {
            fireFocus(getParent(t));
            stop = true;
        } else if ('Home' === key) {
            fireFocus(getElement(targets, getParent(t)));
            stop = true;
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }

    function onKeyDownFiles(e) {
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
            let any;
            if ('ArrowDown' === key || 'Home' === key) {
                fireFocus(getElement(targets, t));
                stop = true;
            } else if ('ArrowUp' === key || 'End' === key) {
                any = [].slice.call(getElements(targets, t));
                fireFocus(any.pop());
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }
    W._.on('change', onChange);
})();