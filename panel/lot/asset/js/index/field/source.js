(function() {
    'use strict';
    var hasValue = function hasValue(x, data) {
        return -1 !== data.indexOf(x);
    };
    var isArray = function isArray(x) {
        return Array.isArray(x);
    };
    var isDefined = function isDefined(x) {
        return 'undefined' !== typeof x;
    };
    var isFunction = function isFunction(x) {
        return 'function' === typeof x;
    };
    var isInstance = function isInstance(x, of ) {
        return x && isSet$1( of ) && x instanceof of ;
    };
    var isNull = function isNull(x) {
        return null === x;
    };
    var isNumeric = function isNumeric(x) {
        return /^-?(?:\d*.)?\d+$/.test(x + "");
    };
    var isObject = function isObject(x, isPlain) {
        if (isPlain === void 0) {
            isPlain = true;
        }
        if ('object' !== typeof x) {
            return false;
        }
        return isPlain ? isInstance(x, Object) : true;
    };
    var isSet$1 = function isSet(x) {
        return isDefined(x) && !isNull(x);
    };
    var isString = function isString(x) {
        return 'string' === typeof x;
    };
    var toCount = function toCount(x) {
        return x.length;
    };
    var toEdge = function toEdge(x, edges) {
        if (isSet$1(edges[0]) && x < edges[0]) {
            return edges[0];
        }
        if (isSet$1(edges[1]) && x > edges[1]) {
            return edges[1];
        }
        return x;
    };
    var toNumber = function toNumber(x, base) {
        if (base === void 0) {
            base = 10;
        }
        return base ? parseInt(x, base) : parseFloat(x);
    };
    var toObjectCount = function toObjectCount(x) {
        return toCount(toObjectKeys(x));
    };
    var toObjectKeys = function toObjectKeys(x) {
        return Object.keys(x);
    };
    var toObjectValues = function toObjectValues(x) {
        return Object.values(x);
    };
    var toValue = function toValue(x) {
        if (isArray(x)) {
            return x.map(function(v) {
                return toValue(v);
            });
        }
        if (isNumeric(x)) {
            return toNumber(x);
        }
        if (isObject(x)) {
            for (var k in x) {
                x[k] = toValue(x[k]);
            }
            return x;
        }
        if ('false' === x) {
            return false;
        }
        if ('null' === x) {
            return null;
        }
        if ('true' === x) {
            return true;
        }
        return x;
    };
    var fromHTML = function fromHTML(x) {
        return x.replace(/&/g, '&amp;').replace(/>/g, '&gt;').replace(/</g, '&lt;');
    };
    var fromJSON = function fromJSON(x) {
        var value = null;
        try {
            value = JSON.parse(x);
        } catch (e) {}
        return value;
    };
    var fromStates = function fromStates() {
        for (var _len = arguments.length, lot = new Array(_len), _key = 0; _key < _len; _key++) {
            lot[_key] = arguments[_key];
        }
        var out = lot.shift();
        for (var i = 0, j = toCount(lot); i < j; ++i) {
            for (var k in lot[i]) {
                // Assign value
                if (!isSet$1(out[k])) {
                    out[k] = lot[i][k];
                    continue;
                } // Merge array
                if (isArray(out[k]) && isArray(lot[i][k])) {
                    out[k] = [
                        /* Clone! */
                    ].concat(out[k]);
                    for (var ii = 0, jj = toCount(lot[i][k]); ii < jj; ++ii) {
                        if (!hasValue(lot[i][k][ii], out[k])) {
                            out[k].push(lot[i][k][ii]);
                        }
                    } // Merge object recursive
                } else if (isObject(out[k]) && isObject(lot[i][k])) {
                    out[k] = fromStates({
                        /* Clone! */
                    }, out[k], lot[i][k]); // Replace value
                } else {
                    out[k] = lot[i][k];
                }
            }
        }
        return out;
    };
    var fromValue = function fromValue(x) {
        if (isArray(x)) {
            return x.map(function(v) {
                return fromValue(x);
            });
        }
        if (isObject(x)) {
            for (var k in x) {
                x[k] = fromValue(x[k]);
            }
            return x;
        }
        if (false === x) {
            return 'false';
        }
        if (null === x) {
            return 'null';
        }
        if (true === x) {
            return 'true';
        }
        return "" + x;
    };
    var D = document;
    var W = window;
    var B = D.body;
    var R = D.documentElement;
    var getAttribute = function getAttribute(node, attribute, parseValue) {
        if (parseValue === void 0) {
            parseValue = true;
        }
        if (!hasAttribute(node, attribute)) {
            return null;
        }
        var value = node.getAttribute(attribute);
        return parseValue ? toValue(value) : value;
    };
    var getDatum = function getDatum(node, datum, parseValue) {
        if (parseValue === void 0) {
            parseValue = true;
        }
        var value = getAttribute(node, 'data-' + datum, parseValue),
            v = (value + "").trim();
        if (parseValue && v && ('[' === v[0] && ']' === v.slice(-1) || '{' === v[0] && '}' === v.slice(-1)) && null !== (v = fromJSON(value))) {
            return v;
        }
        return value;
    };
    var getElements = function getElements(query, scope) {
        return (scope || D).querySelectorAll(query);
    };
    var hasAttribute = function hasAttribute(node, attribute) {
        return node.hasAttribute(attribute);
    };
    var theLocation = W.location;
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
    var esc = function esc(pattern, extra) {
        if (extra === void 0) {
            extra = "";
        }
        return pattern.replace(toPattern('[' + extra + x.replace(/./g, '\\$&') + ']'), '\\$&');
    };
    var isPattern = function isPattern(pattern) {
        return isInstance(pattern, RegExp);
    };
    var toPattern = function toPattern(pattern, opt) {
        if (isPattern(pattern)) {
            return pattern;
        } // No need to escape `/` in the pattern string
        pattern = pattern.replace(/\//g, '\\/');
        return new RegExp(pattern, isSet$1(opt) ? opt : 'g');
    };
    var x = "!$^*()+=[]{}|:<>,.?/-";
    var name = 'TE';

    function trim(str, dir) {
        return (str || "")['trim' + (-1 === dir ? 'Left' : 1 === dir ? 'Right' : "")]();
    }

    function TE(source, state) {
        if (state === void 0) {
            state = {};
        }
        var $ = this;
        if (!source) {
            return $;
        } // Already instantiated, skip!
        if (source[name]) {
            return source[name];
        } // Return new instance if `TE` was called without the `new` operator
        if (!isInstance($, TE)) {
            return new TE(source, state);
        }
        $.state = state = fromStates({}, TE.state, isString(state) ? {
            tab: state
        } : state || {}); // The `<textarea>` element
        $.self = $.source = source; // Store current instance to `TE.instances`
        TE.instances[source.id || source.name || toObjectCount(TE.instances)] = $; // Mark current DOM as active text editor to prevent duplicate instance
        source[name] = $;
        var any = /^([\s\S]*?)$/,
            // Any character(s)
            sourceIsDisabled = function sourceIsDisabled() {
                return source.disabled;
            },
            sourceIsReadOnly = function sourceIsReadOnly() {
                return source.readOnly;
            },
            sourceValue = function sourceValue() {
                return source.value.replace(/\r/g, "");
            }; // The initial value
        $.value = sourceValue(); // Get value
        $.get = function() {
            return !sourceIsDisabled() && trim(sourceValue()) || null;
        }; // Reset to the initial value
        $.let = function() {
            return source.value = $.value, $;
        }; // Set value
        $.set = function(value) {
            if (sourceIsDisabled() || sourceIsReadOnly()) {
                return $;
            }
            return source.value = value, $;
        }; // Get selection
        $.$ = function() {
            return new TE.S(source.selectionStart, source.selectionEnd, sourceValue());
        };
        $.focus = function(mode) {
            var x, y;
            if (-1 === mode) {
                x = y = 0; // Put caret at the start of the editor, scroll to the start of the editor
            } else if (1 === mode) {
                x = toCount(sourceValue()); // Put caret at the end of the editor
                y = source.scrollHeight; // Scroll to the end of the editor
            }
            if (isSet$1(x) && isSet$1(y)) {
                source.selectionStart = source.selectionEnd = x;
                source.scrollTop = y;
            }
            return source.focus(), $;
        }; // Blur from the editor
        $.blur = function() {
            return source.blur(), $;
        }; // Select value
        $.select = function() {
            if (sourceIsDisabled() || sourceIsReadOnly()) {
                return source.focus(), $;
            }
            for (var _len = arguments.length, lot = new Array(_len), _key = 0; _key < _len; _key++) {
                lot[_key] = arguments[_key];
            }
            var count = toCount(lot),
                _$$$ = $.$(),
                start = _$$$.start,
                end = _$$$.end,
                x,
                y,
                X,
                Y;
            x = W.pageXOffset || R.scrollLeft || B.scrollLeft;
            y = W.pageYOffset || R.scrollTop || B.scrollTop;
            X = source.scrollLeft;
            Y = source.scrollTop;
            if (0 === count) {
                // Restore selection with `$.select()`
                lot[0] = start;
                lot[1] = end;
            } else if (1 === count) {
                // Move caret position with `$.select(7)`
                if (true === lot[0]) {
                    // Select all with `$.select(true)`
                    return source.focus(), source.select(), $;
                }
                lot[1] = lot[0];
            }
            source.focus(); // Default `$.select(7, 100)`
            source.selectionStart = lot[0];
            source.selectionEnd = lot[1];
            source.scrollLeft = X;
            source.scrollTop = Y;
            return W.scroll(x, y), $;
        }; // Match at selection
        $.match = function(pattern, then) {
            var _$$$2 = $.$(),
                after = _$$$2.after,
                before = _$$$2.before,
                value = _$$$2.value;
            if (isArray(pattern)) {
                var _m = [before.match(pattern[0]), value.match(pattern[1]), after.match(pattern[2])];
                return isFunction(then) ? then.call($, _m[0] || [], _m[1] || [], _m[2] || []) : [!!_m[0], !!_m[1], !!_m[2]];
            }
            var m = value.match(pattern);
            return isFunction(then) ? then.call($, m || []) : !!m;
        }; // Replace at selection
        $.replace = function(from, to, mode) {
            var _$$$3 = $.$(),
                after = _$$$3.after,
                before = _$$$3.before,
                value = _$$$3.value;
            if (-1 === mode) {
                // Replace before
                before = before.replace(from, to);
            } else if (1 === mode) {
                // Replace after
                after = after.replace(from, to);
            } else {
                // Replace value
                value = value.replace(from, to);
            }
            return $.set(before + value + after).select(before = toCount(before), before + toCount(value));
        }; // Insert/replace at caret
        $.insert = function(value, mode, clear) {
            var from = any;
            if (clear) {
                $.replace(from, ""); // Force to delete selection on insert before/after?
            }
            if (-1 === mode) {
                // Insert before
                from = /$/;
            } else if (1 === mode) {
                // Insert after
                from = /^/;
            }
            return $.replace(from, value, mode);
        }; // Wrap current selection
        $.wrap = function(open, close, wrap) {
            var _$$$4 = $.$(),
                after = _$$$4.after,
                before = _$$$4.before,
                value = _$$$4.value;
            if (wrap) {
                return $.replace(any, open + '$1' + close);
            }
            return $.set(before + open + value + close + after).select(before = toCount(before + open), before + toCount(value));
        }; // Unwrap current selection
        $.peel = function(open, close, wrap) {
            var _$$$5 = $.$(),
                after = _$$$5.after,
                before = _$$$5.before,
                value = _$$$5.value;
            open = esc(open);
            close = esc(close);
            var openPattern = toPattern(open + '$', ""),
                closePattern = toPattern('^' + close, "");
            if (wrap) {
                return $.replace(toPattern('^' + open + '([\\s\\S]*?)' + close + '$', ""), '$1');
            }
            if (openPattern.test(before) && closePattern.test(after)) {
                before = before.replace(openPattern, "");
                after = after.replace(closePattern, "");
                return $.set(before + value + after).select(before = toCount(before), before + toCount(value));
            }
            return $.select();
        };
        $.pull = function(by, includeEmptyLines) {
            if (includeEmptyLines === void 0) {
                includeEmptyLines = true;
            }
            var _$$$6 = $.$(),
                length = _$$$6.length,
                value = _$$$6.value;
            by = esc(isSet$1(by) ? by : state.tab);
            if (length) {
                if (includeEmptyLines) {
                    return $.replace(toPattern('^' + by, 'gm'), "");
                }
                return $.insert(value.split('\n').map(function(v) {
                    if (toPattern('^(' + by + ')*$', "").test(v)) {
                        return v;
                    }
                    return v.replace(toPattern('^' + by, ""), "");
                }).join('\n'));
            }
            return $.replace(toPattern(by + '$', ""), "", -1);
        };
        $.push = function(by, includeEmptyLines) {
            if (includeEmptyLines === void 0) {
                includeEmptyLines = false;
            }
            var _$$$7 = $.$(),
                length = _$$$7.length;
            by = isSet$1(by) ? by : state.tab;
            if (length) {
                return $.replace(toPattern('^' + (includeEmptyLines ? "" : '(?!$)'), 'gm'), by);
            }
            return $.insert(by, -1);
        };
        $.trim = function(open, close, start, end, tidy) {
            if (tidy === void 0) {
                tidy = true;
            }
            if (null !== open && false !== open) {
                open = open || "";
            }
            if (null !== close && false !== close) {
                close = close || "";
            }
            if (null !== start && false !== start) {
                start = start || "";
            }
            if (null !== end && false !== end) {
                end = end || "";
            }
            var _$$$8 = $.$(),
                before = _$$$8.before,
                value = _$$$8.value,
                after = _$$$8.after,
                beforeClean = trim(before, 1),
                afterClean = trim(after, -1);
            before = false !== open ? trim(before, 1) + (beforeClean || !tidy ? open : "") : before;
            after = false !== close ? (afterClean || !tidy ? close : "") + trim(after, -1) : after;
            if (false !== start) value = trim(value, -1);
            if (false !== end) value = trim(value, 1);
            return $.set(before + value + after).select(before = toCount(before), before + toCount(value));
        }; // Destructor
        $.pop = function() {
            if (!source[name]) {
                return $; // Already ejected!
            }
            return delete source[name], $;
        }; // Return the text editor state
        $.state = state;
        return $;
    }
    TE.esc = esc;
    TE.instances = {};
    TE.state = {
        'tab': '\t'
    };
    TE.S = function(a, b, c) {
        var t = this,
            d = c.slice(a, b);
        t.after = c.slice(b);
        t.before = c.slice(0, a);
        t.end = b;
        t.length = toCount(d);
        t.start = a;
        t.value = d;
        t.toString = function() {
            return d;
        };
    };
    TE.version = '3.3.9';
    TE.x = x;
    var that$2 = {};
    that$2._history = [];
    that$2._historyState = -1; // Get history data
    that$2.history = function(index) {
        var t = this;
        if (!isSet$1(index)) {
            return t._history;
        }
        return isSet$1(t._history[index]) ? t._history[index] : null;
    }; // Remove state from history
    that$2.loss = function(index) {
        var t = this,
            current;
        if (true === index) {
            t._history = [];
            t._historyState = -1;
            return [];
        }
        current = t._history.splice(isSet$1(index) ? index : t._historyState, 1);
        t._historyState = toEdge(t._historyState - 1, [-1]);
        return current;
    }; // Save current state to history
    that$2.record = function(index) {
        var t = this,
            _t$$ = t.$(),
            end = _t$$.end,
            start = _t$$.start,
            current = t._history[t._historyState] || [],
            next = [t.self.value, start, end];
        if (next[0] === current[0] && next[1] === current[1] && next[2] === current[2]) {
            return t; // Do not save duplicate
        }
        ++t._historyState;
        return t._history[isSet$1(index) ? index : t._historyState] = next, t;
    }; // Redo previous state
    that$2.redo = function() {
        var t = this,
            state;
        t._historyState = toEdge(t._historyState + 1, [0, toCount(t._history) - 1]);
        state = t._history[t._historyState];
        return t.set(state[0]).select(state[1], state[2]);
    }; // Undo current state
    that$2.undo = function() {
        var t = this,
            state;
        t._historyState = toEdge(t._historyState - 1, [0, toCount(t._history) - 1]);
        state = t._history[t._historyState];
        return t.set(state[0]).select(state[1], state[2]);
    };
    var debounce = function debounce(then, time) {
        var timer;
        return function() {
            var _arguments = arguments,
                _this = this;
            timer && clearTimeout(timer);
            timer = setTimeout(function() {
                return then.apply(_this, _arguments);
            }, time);
        };
    };
    var pairs = {
        '`': '`',
        '(': ')',
        '{': '}',
        '[': ']',
        '"': '"',
        "'": "'",
        '<': '>'
    };

    function promisify(type, lot) {
        return new Promise(function(resolve, reject) {
            var r = W[type].apply(W, lot);
            return r ? resolve(r) : reject(r);
        });
    }
    var defaults$2 = {
        source: {
            pairs: pairs,
            type: null
        }
    };
    ['alert', 'confirm', 'prompt'].forEach(function(type) {
        defaults$2.source[type] = function() {
            for (var _len = arguments.length, lot = new Array(_len), _key = 0; _key < _len; _key++) {
                lot[_key] = arguments[_key];
            }
            return promisify(type, lot);
        };
    });
    var that$1 = {};
    that$1.toggle = function(open, close, wrap, tidy) {
        if (tidy === void 0) {
            tidy = false;
        }
        if (!close && "" !== close) {
            close = open;
        }
        var t = this,
            _t$$ = t.$(),
            after = _t$$.after,
            before = _t$$.before,
            value = _t$$.value,
            closeCount = toCount(close),
            openCount = toCount(open);
        if (wrap && close === value.slice(-closeCount) && open === value.slice(0, openCount) || close === after.slice(0, closeCount) && open === before.slice(-openCount)) {
            return t.peel(open, close, wrap);
        }
        if (false !== tidy) {
            if (isString(tidy)) {
                tidy = [tidy, tidy];
            } else if (!isArray(tidy)) {
                tidy = ["", ""];
            }
            if (!isSet(tidy[1])) {
                tidy[1] = tidy[0];
            }
            t.trim(tidy[0], tidy[1]);
        }
        return t.wrap(open, close, wrap);
    };
    var CTRL_PREFIX = 'Control-';

    function canKeyDown$2(map, that) {
        var charAfter,
            charBefore,
            charIndent = that.state.source.tab || that.state.tab || '\t',
            charPairs = that.state.source.pairs || {},
            charPairsValues = toObjectValues(charPairs),
            key = map.key,
            queue = map.queue,
            keyValue = map + ""; // Do nothing
        if (queue.Alt || queue.Control) {
            return true;
        }
        if (' ' === keyValue) {
            var _that$$ = that.$(),
                _after = _that$$.after,
                _before = _that$$.before,
                _value = _that$$.value;
            charAfter = charPairs[charBefore = _before.slice(-1)];
            if (!_value && charAfter && charBefore && charAfter === _after[0]) {
                that.wrap(' ', ' ');
                return false;
            }
            return true;
        }
        if ('Enter' === keyValue) {
            var _that$$2 = that.$(),
                _after2 = _that$$2.after,
                _before2 = _that$$2.before,
                _value2 = _that$$2.value,
                lineBefore = _before2.split('\n').pop(),
                lineMatch = lineBefore.match(/^(\s+)/),
                lineMatchIndent = lineMatch && lineMatch[1] || "";
            if (!_value2) {
                if (_after2 && _before2 && (charAfter = charPairs[charBefore = _before2.slice(-1)]) && charAfter === _after2[0]) {
                    that.wrap('\n' + lineMatchIndent + (charBefore !== charAfter ? charIndent : ""), '\n' + lineMatchIndent).record();
                    return false;
                }
                if (lineMatchIndent) {
                    that.insert('\n' + lineMatchIndent, -1).record();
                    return false;
                }
            }
            return true;
        }
        if ('Backspace' === keyValue) {
            var _that$$3 = that.$(),
                _after3 = _that$$3.after,
                _before3 = _that$$3.before,
                _value3 = _that$$3.value;
            _after3.split('\n')[0];
            var _lineBefore = _before3.split('\n').pop(),
                _lineMatch = _lineBefore.match(/^(\s+)/),
                _lineMatchIndent = _lineMatch && _lineMatch[1] || "";
            charAfter = charPairs[charBefore = _before3.slice(-1)]; // Do nothing on escape
            if ('\\' === charBefore) {
                return true;
            }
            if (_value3) {
                if (_after3 && _before3 && charAfter && charAfter === _after3[0] && !_before3.endsWith('\\' + charBefore)) {
                    that.record().peel(charBefore, charAfter).record();
                    return false;
                }
                return true;
            }
            charAfter = charPairs[charBefore = _before3.trim().slice(-1)];
            if (charAfter && charBefore) {
                if (_after3.startsWith(' ' + charAfter) && _before3.endsWith(charBefore + ' ') || _after3.startsWith('\n' + _lineMatchIndent + charAfter) && _before3.endsWith(charBefore + '\n' + _lineMatchIndent)) {
                    // Collapse bracket(s)
                    that.trim("", "").record();
                    return false;
                }
            } // Outdent
            if (_lineBefore.endsWith(charIndent)) {
                that.pull(charIndent).record();
                return false;
            }
            if (_after3 && _before3 && !_before3.endsWith('\\' + charBefore)) {
                if (charAfter === _after3[0] && charBefore === _before3.slice(-1)) {
                    // Peel pair
                    that.peel(charBefore, charAfter).record();
                    return false;
                }
            }
            return true;
        }
        var _that$$4 = that.$(),
            after = _that$$4.after,
            before = _that$$4.before,
            start = _that$$4.start,
            value = _that$$4.value; // Do nothing on escape
        if ('\\' === (charBefore = before.slice(-1))) {
            return true;
        }
        charAfter = hasValue(after[0], charPairsValues) ? after[0] : charPairs[charBefore]; // `|}`
        if (!value && after && before && charAfter && key === charAfter) {
            // Move to the next character
            // `}|`
            that.select(start + 1).record();
            return false;
        }
        for (charBefore in charPairs) {
            charAfter = charPairs[charBefore]; // `{|`
            if (key === charBefore && charAfter) {
                // Wrap pair or selection
                // `{|}` `{|aaa|}`
                that.wrap(charBefore, charAfter).record();
                return false;
            } // `|}`
            if (key === charAfter) {
                if (value) {
                    // Wrap selection
                    // `{|aaa|}`
                    that.record().wrap(charBefore, charAfter).record();
                    return false;
                }
                break;
            }
        }
        return true;
    }

    function canKeyDownDent(map, that) {
        var charIndent = that.state.source.tab || that.state.tab || '\t';
        map.key;
        map.queue;
        var keyValue = map + ""; // Indent with `⎈]`
        if (CTRL_PREFIX + ']' === keyValue) {
            that.push(charIndent).record();
            return false;
        } // Outdent with `⎈[`
        if (CTRL_PREFIX + '[' === keyValue) {
            that.pull(charIndent).record();
            return false;
        }
        return true;
    }

    function canKeyDownEnter(map, that) {
        map.key;
        var queue = map.queue;
        if (queue.Control && queue.Enter) {
            var _that$$5 = that.$(),
                after = _that$$5.after,
                before = _that$$5.before,
                end = _that$$5.end,
                start = _that$$5.start,
                value = _that$$5.value,
                lineAfter = after.split('\n').shift(),
                lineBefore = before.split('\n').pop(),
                lineMatch = lineBefore.match(/^(\s+)/),
                lineMatchIndent = lineMatch && lineMatch[1] || "";
            if (before || after) {
                if (queue.Shift) {
                    // Insert line over with `⎈⇧↵`
                    return that.select(start - toCount(lineBefore)).wrap(lineMatchIndent, '\n').insert(value).record(), false;
                } // Insert line below with `⎈↵`
                return that.select(end + toCount(lineAfter)).wrap('\n' + lineMatchIndent, "").insert(value).record(), false;
            }
        }
        return true;
    }

    function canKeyDownHistory(map, that) {
        var keyValue = map + ""; // Redo with `⎈y`
        if (CTRL_PREFIX + 'y' === keyValue) {
            return that.redo(), false;
        } // Undo with `⎈z`
        if (CTRL_PREFIX + 'z' === keyValue) {
            return that.undo(), false;
        }
        return true;
    }

    function canKeyDownMove(map, that) {
        map.key;
        var queue = map.queue,
            keyValue = map + "";
        if (!queue.Control) {
            return true;
        }
        var _that$$6 = that.$(),
            after = _that$$6.after,
            before = _that$$6.before,
            end = _that$$6.end,
            start = _that$$6.start,
            value = _that$$6.value,
            charPair,
            charPairValue,
            charPairs = that.state.source.pairs || {},
            boundaries = [],
            m;
        if (value) {
            for (charPair in charPairs) {
                if (!(charPairValue = charPairs[charPair])) {
                    continue;
                }
                boundaries.push('(?:\\' + charPair + '(?:\\\\.|[^\\' + charPair + (charPairValue !== charPair ? '\\' + charPairValue : "") + '])*\\' + charPairValue + ')');
            }
            boundaries.push('\\w+'); // Word(s)
            boundaries.push('\\s+'); // White-space(s)
            boundaries.push('[\\s\\S]'); // Last try!
            if (CTRL_PREFIX + 'ArrowLeft' === keyValue) {
                if (m = before.match(toPattern('(' + boundaries.join('|') + ')$', ""))) {
                    that.insert("").select(start - toCount(m[0])).insert(value);
                    return that.record(), false;
                }
                return that.select(), false;
            }
            if (CTRL_PREFIX + 'ArrowRight' === keyValue) {
                if (m = after.match(toPattern('^(' + boundaries.join('|') + ')', ""))) {
                    that.insert("").select(end + toCount(m[0]) - toCount(value)).insert(value);
                    return that.record(), false;
                }
                return that.select(), false;
            }
        }
        var lineAfter = after.split('\n').shift(),
            lineBefore = before.split('\n').pop(),
            lineMatch = lineBefore.match(/^(\s+)/);
        lineMatch && lineMatch[1] || ""; // Force to select the current line if there is no selection
        end += toCount(lineAfter);
        start -= toCount(lineBefore);
        value = lineBefore + value + lineAfter;
        if (CTRL_PREFIX + 'ArrowUp' === keyValue) {
            if (!hasValue('\n', before)) {
                return that.select(), false;
            }
            that.insert("");
            that.replace(/^([^\n]*?)(\n|$)/, '$2', 1);
            that.replace(/(^|\n)([^\n]*?)$/, "", -1);
            var $ = that.$();
            before = $.before;
            start = $.start;
            lineBefore = before.split('\n').pop();
            that.select(start = start - toCount(lineBefore)).wrap(value, '\n');
            that.select(start, start + toCount(value));
            return that.record(), false;
        }
        if (CTRL_PREFIX + 'ArrowDown' === keyValue) {
            if (!hasValue('\n', after)) {
                return that.select(), false;
            }
            that.insert("");
            that.replace(/^([^\n]*?)(\n|$)/, "", 1);
            that.replace(/(^|\n)([^\n]*?)$/, '$1', -1);
            var _$ = that.$();
            after = _$.after;
            end = _$.end;
            lineAfter = after.split('\n').shift();
            that.select(end = end + toCount(lineAfter)).wrap('\n', value);
            end += 1;
            that.select(end, end + toCount(value));
            return that.record(), false;
        }
        return true;
    }
    var bounce = debounce(function(that) {
        return that.record();
    }, 100);

    function canKeyUp(map, that) {
        return bounce(that), true;
    }
    var state$2 = defaults$2;
    var tagComment = '<!--([\\s\\S](?!-->)*)-->',
        tagData = '<!((?:\'(?:\\\\.|[^\'])*\'|"(?:\\\\.|[^"])*"|[^>\'"])*)>',
        tagName$1 = '[\\w:.-]+',
        tagStart$1 = function tagStart(name) {
            return '<(' + name + ')(\\s(?:\'(?:\\\\.|[^\'])*\'|"(?:\\\\.|[^"])*"|[^/>\'"])*)?>';
        },
        tagEnd$1 = function tagEnd(name) {
            return '</(' + name + ')>';
        },
        tagVoid = function tagVoid(name) {
            return '<(' + name + ')(\\s(?:\'(?:\\\\.|[^\'])*\'|"(?:\\\\.|[^"])*"|[^/>\'"])*)?/?>';
        },
        tagPreamble = '<\\?((?:\'(?:\\\\.|[^\'])*\'|"(?:\\\\.|[^"])*"|[^>\'"])*)\\?>',
        tagTokens = '(?:' + tagComment + '|' + tagData + '|' + tagEnd$1(tagName$1) + '|' + tagPreamble + '|' + tagStart$1(tagName$1) + '|' + tagVoid(tagName$1) + ')';
    var defaults$1 = {
        source: {
            type: 'XML'
        },
        sourceXML: {}
    };
    var that = {};

    function toAttributes(attributes) {
        if (!attributes) {
            return "";
        } // Sort object by key(s)
        attributes = toObjectKeys(attributes).sort().reduce(function(r, k) {
            return r[k] = attributes[k], r;
        }, {});
        var attribute,
            v,
            out = "";
        for (attribute in attributes) {
            v = attributes[attribute];
            if (false === v || null === v) {
                continue;
            }
            out += ' ' + attribute;
            if (true !== v) {
                out += '="' + fromHTML(fromValue(v)) + '"';
            }
        }
        return out;
    }

    function toTidy$1(tidy) {
        if (false !== tidy) {
            if (isString(tidy)) {
                tidy = [tidy, tidy];
            } else if (!isArray(tidy)) {
                tidy = ["", ""];
            }
            if (!isSet$1(tidy[1])) {
                tidy[1] = tidy[0];
            }
        }
        return tidy; // Return `[…]` or `false`
    }
    that.insert = function(name, content, attributes, tidy) {
        if (content === void 0) {
            content = "";
        }
        if (attributes === void 0) {
            attributes = {};
        }
        if (tidy === void 0) {
            tidy = false;
        }
        var t = this;
        if (false !== (tidy = toTidy$1(tidy))) {
            t.trim(tidy[0], "");
        }
        return t.insert('<' + name + toAttributes(attributes) + (false !== content ? '>' + content + '</' + name + '>' : ' />') + (false !== tidy ? tidy[1] : ""), -1, true);
    };
    that.toggle = function(name, content, attributes, tidy) {
        if (content === void 0) {
            content = "";
        }
        if (attributes === void 0) {
            attributes = {};
        }
        if (tidy === void 0) {
            tidy = false;
        }
        var t = this,
            _t$$ = t.$(),
            after = _t$$.after,
            before = _t$$.before,
            value = _t$$.value,
            tagStartLocal = tagStart$1(name),
            tagEndLocal = tagEnd$1(name),
            tagStartLocalPattern = toPattern(tagStartLocal + '$', ""),
            tagEndLocalPattern = toPattern('^' + tagEndLocal, ""),
            tagStartLocalMatch = tagStartLocalPattern.test(before),
            tagEndLocalMatch = tagEndLocalPattern.test(after);
        if (tagEndLocalMatch && tagStartLocalMatch) {
            return t.replace(tagEndLocalPattern, "", 1).replace(tagStartLocalPattern, "", -1);
        }
        tagStartLocalPattern = toPattern('^' + tagStartLocal, "");
        tagEndLocalPattern = toPattern(tagEndLocal + '$', "");
        tagStartLocalMatch = tagStartLocalPattern.test(value);
        tagEndLocalMatch = tagEndLocalPattern.test(value);
        if (tagEndLocalMatch && tagStartLocalMatch) {
            t.insert(value = value.replace(tagEndLocalPattern, "").replace(tagStartLocalPattern, ""));
        }
        if (!value && content) {
            t.insert(content);
        }
        if (false !== (tidy = toTidy$1(tidy))) {
            t.trim(tidy[0], tidy[1]);
        }
        return t.wrap('<' + name + toAttributes(attributes) + '>', '</' + name + '>');
    };
    that.wrap = function(name, content, attributes, tidy) {
        if (content === void 0) {
            content = "";
        }
        if (attributes === void 0) {
            attributes = {};
        }
        if (tidy === void 0) {
            tidy = false;
        }
        var t = this,
            _t$$2 = t.$();
        _t$$2.after;
        _t$$2.before;
        var value = _t$$2.value;
        if (!value && content) {
            t.insert(content);
        }
        if (false !== (tidy = toTidy$1(tidy))) {
            t.trim(tidy[0], tidy[1]);
        }
        return t.wrap('<' + name + toAttributes(attributes) + '>', '</' + name + '>');
    };

    function canKeyDown$1(map, that) {
        var state = that.state,
            charIndent = state.sourceXML.tab || state.tab || '\t',
            key = map.key,
            queue = map.queue,
            keyValue = map + ""; // Do nothing
        if (queue.Alt || queue.Control) {
            return true;
        }
        if (['-', '>', '/', '?', ' '].includes(key)) {
            var _that$$ = that.$(),
                after = _that$$.after,
                before = _that$$.before,
                value = _that$$.value,
                start = _that$$.start;
            if ('-' === key) {
                // `<!-|`
                if (!value && '<!-' === before.slice(-3)) {
                    that.wrap('- ', ' --' + ('>' === after[0] ? "" : '>')).record();
                    return false;
                }
            }
            if ('>' === key || '/' === key) {
                var tagStartMatch = toPattern(tagStart$1(tagName$1) + '$', "").exec(before + '>');
                if (!value && tagStartMatch) {
                    // `<div|`
                    if ('/' === key) {
                        // `<div|>`
                        if ('>' === after[0]) {
                            that.trim("", false).insert(' /', -1).select(that.$().start + 1).record();
                            return false;
                        }
                        that.trim("", false).insert(' />', -1).record();
                        return false;
                    } // `<div|></div>`
                    if (after.startsWith('></' + tagStartMatch[1] + '>')) {
                        that.select(start + 1).record(); // `<div|</div>`
                    } else if (after.startsWith('</' + tagStartMatch[1] + '>')) {
                        that.insert('>', -1).record(); // `<div|`
                    } else {
                        that.wrap('>', '</' + tagStartMatch[1] + ('>' === after[0] ? "" : '>')).record();
                    }
                    return false;
                }
            }
            if ('?' === key) {
                // `<|`
                if (!value && '<' === before.slice(-1)) {
                    that.wrap('?', '?' + ('>' === after[0] ? "" : '>')).record();
                    return false;
                }
            }
            if (' ' === keyValue) {
                if (!value) {
                    if ( // `<!--|-->`
                        '-->' === after.slice(0, 3) && '<!--' === before.slice(-4) || // `<?foo|?>`
                        '?>' === after.slice(0, 2) && '<?' === before.slice(0, 2) && /<\?\S*$/.test(before)) {
                        that.wrap(' ', ' ').record();
                        return false;
                    }
                }
            }
        }
        if ('ArrowLeft' === keyValue) {
            var _that$$2 = that.$(),
                _before = _that$$2.before,
                _start = _that$$2.start,
                _value = _that$$2.value;
            if (!_value) {
                var tagMatch = toPattern(tagTokens + '$', "").exec(_before); // `<foo>|bar`
                if (tagMatch) {
                    that.select(_start - toCount(tagMatch[0]), _start);
                    return false;
                }
            }
        }
        if ('ArrowRight' === keyValue) {
            var _that$$3 = that.$(),
                _after = _that$$3.after,
                _start2 = _that$$3.start,
                _value2 = _that$$3.value;
            if (!_value2) {
                var _tagMatch = toPattern('^' + tagTokens, "").exec(_after); // `foo|<bar>`
                if (_tagMatch) {
                    that.select(_start2, _start2 + toCount(_tagMatch[0]));
                    return false;
                }
            }
        }
        if ('Enter' === keyValue) {
            var _that$$4 = that.$(),
                _after2 = _that$$4.after,
                _before2 = _that$$4.before,
                _value3 = _that$$4.value,
                lineBefore = _before2.split('\n').pop(),
                lineMatch = lineBefore.match(/^(\s+)/),
                lineMatchIndent = lineMatch && lineMatch[1] || "",
                _tagStartMatch = _before2.match(toPattern(tagStart$1(tagName$1) + '$', ""));
            if (!_value3) {
                if ( // `<!--|-->`
                    /^[ \t]*-->/.test(_after2) && /<!--[ \t]*$/.test(_before2) || // `<?foo|?>`
                    /^[ \t]*\?>/.test(_after2) && /<\?\S*[ \t]*$/.test(_before2)) {
                    that.trim().wrap('\n' + lineMatchIndent, '\n' + lineMatchIndent).record();
                    return false;
                }
                if (_tagStartMatch) {
                    if (_after2.startsWith('</' + _tagStartMatch[1] + '>')) {
                        that.record().trim().wrap('\n' + lineMatchIndent + charIndent, '\n' + lineMatchIndent).record();
                    } else {
                        that.record().wrap('\n' + lineMatchIndent + charIndent, '\n' + lineMatchIndent + '</' + _tagStartMatch[1] + '>').record();
                    }
                    return false;
                }
            }
        }
        if ('Backspace' === keyValue) {
            var _that$$5 = that.$(),
                _after3 = _that$$5.after,
                _before3 = _that$$5.before,
                _value4 = _that$$5.value;
            if (!_value4) {
                // `<!--|`
                if ('<!--' === _before3.slice(-4)) {
                    that.replace(/<!--$/, "", -1); // `<!--|-->`
                    if ('-->' === _after3.slice(0, 3)) {
                        that.replace(/^-->/, "", 1);
                    }
                    that.record();
                    return false;
                }
                if (/^\s+-->/.test(_after3) && /<!--\s+$/.test(_before3)) {
                    that.trim(' ' === _before3.slice(-1) ? "" : ' ', ' ' === _after3[0] ? "" : ' ').record();
                    return false;
                } // `<?|`
                if (/<\?\S*$/.test(_before3)) {
                    that.replace(/<\?\S*$/, "", -1); // `<?|?>`
                    if ('?>' === _after3.slice(0, 2)) {
                        that.replace(/^\?>/, "", 1);
                    }
                    that.record();
                    return false;
                }
                if (/^\s+\?>/.test(_after3) && /<\?\S*\s+$/.test(_before3)) {
                    that.trim(' ' === _before3.slice(-1) ? "" : ' ', ' ' === _after3[0] ? "" : ' ').record();
                    return false;
                }
                var tagPattern = toPattern(tagTokens + '$', ""),
                    _tagMatch2 = tagPattern.exec(_before3);
                if (_tagMatch2) {
                    // `<div />|`
                    if (' />' === _before3.slice(-3)) {
                        that.replace(/ \/>$/, '/>', -1).record();
                        return false;
                    } // `<div/>|`
                    if ('/>' === _before3.slice(-2)) {
                        that.replace(/\/>$/, '>', -1).record();
                        return false;
                    }
                    that.replace(tagPattern, "", -1);
                    var name = _tagMatch2[0].slice(1).split(/\s+|>/)[0];
                    if (_tagMatch2[0] && '/' !== _tagMatch2[0][1]) {
                        if (_after3.startsWith('</' + name + '>')) {
                            that.replace(toPattern('^</' + name + '>', ""), "", 1);
                        }
                    }
                    that.record();
                    return false;
                }
                if (toPattern(tagStart$1(tagName$1) + '\\n(?:' + esc(charIndent) + ')?$', "").test(_before3) && toPattern('^\\s*' + tagEnd$1(tagName$1), "").test(_after3)) {
                    that.trim().record(); // Collapse!
                    return false;
                }
            }
        }
        if ('Delete' === keyValue) {
            var _that$$6 = that.$(),
                _after4 = _that$$6.after,
                _value5 = _that$$6.value;
            if (!_value5) {
                // `|-->`
                if ('-->' === _after4.slice(0, 3)) {
                    that.replace(/^-->/, "", 1).record();
                    return false;
                } // `|?>`
                if ('?>' === _after4.slice(0, 2)) {
                    that.replace(/^\?>/, "", 1).record();
                    return false;
                }
                var _tagPattern = toPattern('^' + tagTokens, ""),
                    _tagMatch3 = _tagPattern.exec(_after4);
                if (_tagMatch3) {
                    that.replace(_tagPattern, "", 1).record();
                    return false;
                }
            }
        }
        return true;
    }

    function canMouseDown(map, that) {
        map.key;
        var queue = map.queue;
        if (!queue.Control) {
            W.setTimeout(function() {
                var _that$$7 = that.$(),
                    after = _that$$7.after,
                    before = _that$$7.before,
                    value = _that$$7.value;
                if (!value) {
                    var caret = "\uFEFF",
                        tagTokensLocal = tagTokens.split('(' + tagName$1 + ')').join('((?:[\\w:.-]|' + caret + ')+)'),
                        tagTokensLocalPattern = toPattern(tagTokensLocal),
                        content = before + value + caret + after,
                        m,
                        v;
                    while (m = tagTokensLocalPattern.exec(content)) {
                        if (hasValue(caret, m[0])) {
                            that.select(v = m.index, v + toCount(m[0]) - 1);
                            break;
                        }
                    }
                }
            }, 1);
        }
        return true;
    }
    var state$1 = defaults$1;
    var protocol = theLocation.protocol;
    var defaults = {
        source: {
            type: 'HTML'
        },
        sourceHTML: {
            elements: {
                "": ["", 'text goes here…', {}, ""],
                a: ['a', 'link text goes here…', {}],
                area: ['area', false, {}],
                b: ['strong', 'text goes here…', {}],
                base: ['base', false, {
                    href: ""
                }, '\n'],
                blockquote: ['blockquote', "", {}, '\n'],
                br: ['br', false, {},
                    ["", '\n']
                ],
                button: ['button', 'text goes here…', {
                    name: "",
                    type: 'submit'
                }, ' '],
                caption: ['caption', 'caption goes here…', {}, '\n'],
                code: ['code', 'code goes here…', {}, ' '],
                col: ['col', false, {}, '\n'],
                em: ['em', 'text goes here…', {}],
                figcaption: ['figcaption', 'caption goes here…', {}, '\n'],
                figure: ['figure', "", {}, '\n'],
                h1: ['h1', 'title goes here…', {}, '\n'],
                h2: ['h2', 'title goes here…', {}, '\n'],
                h3: ['h3', 'title goes here…', {}, '\n'],
                h4: ['h4', 'title goes here…', {}, '\n'],
                h5: ['h5', 'title goes here…', {}, '\n'],
                h6: ['h6', 'title goes here…', {}, '\n'],
                hr: ['hr', false, {}, '\n'],
                i: ['em', 'text goes here…', {}],
                img: ['img', false, {
                    alt: "",
                    src: ""
                }, ' '],
                input: ['input', false, {
                    name: "",
                    type: 'text'
                }, ' '],
                li: ['li', 'list item goes here…', {}, '\n'],
                link: ['link', false, {
                    href: ""
                }, '\n'],
                meta: ['meta', false, {}, '\n'],
                ol: ['ol', "", {}, '\n'],
                option: ['option', 'option goes here…', {}, '\n'],
                p: ['p', 'paragraph goes here…', {}, '\n'],
                param: ['param', false, {
                    name: ""
                }, '\n'],
                pre: ['pre', 'text goes here…', {}, '\n'],
                q: ['q', 'quote goes here…', {}, ' '],
                script: ['script', "", {}, '\n'],
                select: ['select', "", {
                    name: ""
                }, ' '],
                source: ['source', false, {
                    src: ""
                }, '\n'],
                strong: ['strong', 'text goes here…', {}],
                style: ['style', "", {}, '\n'],
                td: ['td', 'data goes here…', {}, '\n'],
                textarea: ['textarea', 'text goes here…', {
                    name: ""
                }, ' '],
                th: ['th', 'title goes here…', {}, '\n'],
                tr: ['tr', "", {}, '\n'],
                track: ['track', false, {}, '\n'],
                u: ['u', 'text goes here…', {}],
                ul: ['ul', "", {}, '\n'],
                wbr: ['wbr', false, {},
                    ["", '\n']
                ]
            }
        }
    };
    var toggle = that.toggle;
    var tagName = '[\\w:.-]+',
        tagStart = function tagStart(name) {
            return '<(' + name + ')(\\s(?:\'(?:\\\\.|[^\'])*\'|"(?:\\\\.|[^"])*"|[^/>\'"])*)?>';
        },
        tagEnd = function tagEnd(name) {
            return '</(' + name + ')>';
        };

    function toTidy(tidy) {
        if (false !== tidy) {
            if (isString(tidy)) {
                tidy = [tidy, tidy];
            } else if (!isArray(tidy)) {
                tidy = ["", ""];
            }
            if (!isSet$1(tidy[1])) {
                tidy[1] = tidy[0];
            }
        }
        return tidy; // Return `[…]` or `false`
    }

    function toggleBlocks(that) {
        var patternBefore = /<(?:h([1-6])|p)(\s[^>]*)?>$/,
            patternAfter = /^<\/(?:h[1-6]|p)>/;
        that.match([patternBefore, /.*/, patternAfter], function(before, value, after) {
            var t = this,
                h = +(before[1] || 0),
                attr = before[2] || "",
                elements = that.state.sourceHTML.elements || {},
                element = before[0] ? elements[before[0].slice(1, -1).split(/\s/)[0]] : ["", "", {}];
            if (!attr && element[2]) {
                attr = toAttributes(element[2]);
            } // ``
            t.replace(patternBefore, "", -1);
            t.replace(/\n+/g, ' ');
            t.replace(patternAfter, "", 1);
            var tidy = element[3] || elements.h1[3];
            if (false !== (tidy = toTidy(tidy))) {
                t.trim(tidy[0], tidy[1]);
            }
            if (!h) {
                // `<h1>`
                t.wrap('<' + elements.h1[0] + (attr || toAttributes(elements.h1[2])) + '>', '</' + elements.h1[0] + '>');
                if (!value[0] || value[0] === elements.p[1]) {
                    t.insert(elements.h1[1]);
                }
            } else {
                ++h;
                if (h > 6) {
                    // `<p>`
                    t.wrap('<' + elements.p[0] + (attr || toAttributes(elements.p[2])) + '>', '</' + elements.p[0] + '>');
                    if (!value[0] || value[0] === elements.h6[1]) {
                        t.insert(elements.p[1]);
                    }
                } else {
                    // `<h1>`, `<h2>`, `<h3>`, `<h4>`, `<h5>`, `<h6>`
                    t.wrap('<' + elements['h' + h][0] + (attr || toAttributes(elements['h' + h][2])) + '>', '</' + elements['h' + h][0] + '>');
                    if (!value[0] || value[0] === elements.p[1]) {
                        t.insert(elements['h' + h][1]);
                    }
                }
            }
        });
    }

    function toggleCodes(that) {
        var patternBefore = /<(?:pre|code)(?:\s[^>]*)?>(?:\s*<code(?:\s[^>]*)?>)?$/,
            patternAfter = /^(?:<\/code>\s*)?<\/(?:pre|code)>/;
        that.match([patternBefore, /.*/, patternAfter], function(before, value, after) {
            var t = this,
                tidy,
                elements = that.state.sourceHTML.elements; // ``
            t.replace(patternBefore, "", -1);
            t.replace(patternAfter, "", 1);
            if (after[0]) {
                // ``
                if (/^(?:<\/code>\s*)?<\/pre>/.test(after[0])) {
                    tidy = elements[""][3];
                    if (false !== (tidy = toTidy(tidy))) {
                        t.trim(tidy[0], tidy[1]);
                    }
                    t.insert(decode(value[0])); // `<pre><code>…</code></pre>`
                } else if (after[0].slice(0, 7) === '</' + elements.code[0] + '>') {
                    tidy = elements.pre[3];
                    if (false !== (tidy = toTidy(tidy))) {
                        t.trim(tidy[0], tidy[1]);
                    }
                    t.wrap('<' + elements.pre[0] + toAttributes(elements.pre[2]) + '><' + elements.code[0] + toAttributes(elements.code[2]) + '>', '</' + elements.code[0] + '></' + elements.pre[0] + '>');
                } // `<code>…</code>`
            } else {
                tidy = elements.code[3];
                if (false !== (tidy = toTidy(tidy))) {
                    t.trim(tidy[0], tidy[1]);
                }
                t.wrap('<' + elements.code[0] + toAttributes(elements.code[2]) + '>', '</' + elements.code[0] + '>').insert(encode(value[0] || elements.code[1]));
            }
        });
    }

    function toggleQuotes(that) {
        var patternBefore = /<(blockquote|q)(?:\s[^>]*)?>\s*$/,
            patternAfter = /^\s*<\/(blockquote|q)>/;
        that.match([patternBefore, /.*/, patternAfter], function(before, value, after) {
            var t = this,
                tidy,
                state = that.state,
                charIndent = state.sourceHTML.tab || state.source.tab || state.tab || '\t',
                elements = that.state.sourceHTML.elements || {}; // ``
            t.replace(patternBefore, "", -1);
            t.replace(patternAfter, "", 1);
            if (after[0]) {
                // ``
                if (elements.blockquote[0] === after[1]) {
                    if (false !== (tidy = toTidy(elements[""][3]))) {
                        t.trim(tidy[0], tidy[1]);
                    } // `<blockquote>…</blockquote>`
                } else if (elements.q[0] === after[1]) {
                    if (false !== (tidy = toTidy(elements.blockquote[3]))) {
                        t.trim(tidy[0], tidy[1]);
                    }
                    t.wrap('<' + elements.blockquote[0] + toAttributes(elements.blockquote[2]) + '>\n', '\n</' + elements.blockquote[0] + '>').insert(value[0] || elements.blockquote[1]);
                    t.replace(toPattern('(^|\\n)'), '$1' + charIndent);
                } // `<q>…</q>`
            } else {
                if (false !== (tidy = toTidy(elements.q[3]))) {
                    t.trim(tidy[0], tidy[1]);
                }
                t.wrap('<' + elements.q[0] + toAttributes(elements.q[2]) + '>', '</' + elements.q[0] + '>').insert(value[0] || elements.q[1]);
                t.replace(toPattern('(^|\\n)' + charIndent), '$1');
            }
        });
    }

    function encode(x) {
        return x.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    function decode(x) {
        return x.replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&amp;/g, '&');
    }
    var commands = {};
    commands.blocks = function() {
        var that = this;
        return toggleBlocks(that), that.record(), false;
    };
    commands.bold = function() {
        var that = this,
            state = that.state,
            elements = state.sourceHTML.elements || {};
        return toggle.apply(this, elements.b), false;
    };
    commands.code = function() {
        var that = this;
        return toggleCodes(that), that.record(), false;
    };
    commands.image = function(label, placeholder) {
        if (label === void 0) {
            label = 'URL:';
        }
        var that = this,
            _that$$ = that.$(),
            after = _that$$.after,
            before = _that$$.before,
            value = _that$$.value,
            state = that.state,
            elements = state.sourceHTML.elements || {},
            charIndent = state.sourceHTML.tab || state.source.tab || state.tab || '\t',
            lineBefore = before.split('\n').pop(),
            lineMatch = lineBefore.match(/^(\s+)/),
            lineMatchIndent = lineMatch && lineMatch[1] || "",
            prompt = state.source.prompt;
        if (isFunction(prompt)) {
            prompt(label, value && /^https?:\/\/\S+$/.test(value) ? value : placeholder || protocol + '//').then(function(src) {
                if (!src) {
                    that.focus();
                    return;
                }
                var element = elements.img;
                if (value) {
                    element[2].alt = value;
                    that.record(); // Record selection
                }
                var tidy = element[3] || false;
                if (false !== (tidy = toTidy(tidy))) {
                    that.trim(tidy[0], "");
                }
                element[2].src = src;
                if ((!after || '\n' === after[0]) && (!before || '\n' === before.slice(-1))) {
                    tidy = elements.figure[3] || false;
                    if (false !== (tidy = toTidy(tidy))) {
                        that.trim(tidy[0], tidy[1]);
                    }
                    that.insert("");
                    that.wrap(lineMatchIndent + '<' + elements.figure[0] + toAttributes(elements.figure[2]) + '>\n' + lineMatchIndent + charIndent, lineMatchIndent + '\n</' + elements.figure[0] + '>');
                    that.insert('<' + element[0] + toAttributes(element[2]) + '>\n' + lineMatchIndent + charIndent, -1);
                    that.wrap('<' + elements.figcaption[0] + toAttributes(elements.figcaption[2]) + '>', '</' + elements.figcaption[0] + '>').insert(elements.figcaption[1]);
                } else {
                    that.insert('<' + element[0] + toAttributes(element[2]) + '>' + (false !== tidy ? tidy[1] : ""), -1, true);
                }
            });
        }
        return that.record(), false;
    };
    commands.italic = function() {
        var that = this,
            state = that.state,
            elements = state.sourceHTML.elements || {};
        return toggle.apply(this, elements.i), false;
    };
    commands.link = function(label, placeholder) {
        if (label === void 0) {
            label = 'URL:';
        }
        var that = this,
            _that$$2 = that.$(),
            value = _that$$2.value,
            state = that.state,
            elements = state.sourceHTML.elements || {},
            prompt = state.source.prompt;
        if (isFunction(prompt)) {
            prompt(label, value && /^https?:\/\/\S+$/.test(value) ? value : placeholder || protocol + '//').then(function(href) {
                if (!href) {
                    that.focus();
                    return;
                }
                var element = elements.a;
                if (value) {
                    that.record(); // Record selection
                }
                element[2].href = href;
                var local = /[.\/?&#]/.test(href[0]) || /^(data|javascript|mailto):/.test(href) || -1 === href.indexOf('://'),
                    extras = {};
                if (!local) {
                    extras.rel = 'nofollow';
                    extras.target = '_blank';
                }
                var tidy = toTidy(element[3] || false);
                if (false === tidy && !value) {
                    // Tidy link with a space if there is no selection
                    tidy = [' ', ' '];
                }
                toggle.apply(that, [element[0], element[1], fromStates(extras, element[2]), tidy]);
            });
        }
        return that.record(), false;
    };
    commands.quote = function() {
        var that = this;
        return toggleQuotes(that), that.record(), false;
    };
    commands.underline = function() {
        var that = this,
            state = that.state,
            elements = state.sourceHTML.elements || {};
        return toggle.apply(this, elements.u), false;
    };

    function canKeyDown(map, that) {
        var state = that.state,
            charIndent = state.sourceHTML.tab || state.source.tab || state.tab || '\t',
            elements = state.sourceHTML.elements || {},
            key = map.key,
            queue = map.queue;
        if (queue.Control) {
            var _that$$3 = that.$(),
                after = _that$$3.after,
                before = _that$$3.before,
                end = _that$$3.end,
                start = _that$$3.start;
            _that$$3.value;
            var lineAfter = after.split('\n').shift(),
                lineBefore = before.split('\n').pop(),
                lineMatch = lineBefore.match(/^(\s+)/),
                lineMatchIndent = lineMatch && lineMatch[1] || "";
            if ('Enter' === key) {
                var _m = lineAfter.match(toPattern(tagEnd(tagName) + '\\s*$', "")),
                    element = elements[_m && _m[1] || 'p'] || elements.p;
                element[3] = ['\n' + lineMatchIndent, '\n' + lineMatchIndent];
                that.select(queue.Shift ? start - toCount(lineBefore) : end + toCount(lineAfter));
                toggle.apply(that, element);
                return that.record(), false;
            }
        } // Do nothing
        if (queue.Alt || queue.Control) {
            return true;
        }
        if ('>' === key) {
            var _that$$4 = that.$(),
                _after = _that$$4.after,
                _before = _that$$4.before,
                _end = _that$$4.end,
                _lineBefore = _before.split('\n').pop(),
                _m2 = (_lineBefore + '>').match(toPattern(tagStart(tagName) + '$', "")),
                _n,
                _element = elements[_n = _m2 && _m2[1] || ""];
            if (!_n) {
                return true;
            }
            if (_element) {
                if (false !== _element[1]) {
                    if ('>' === _after[0]) {
                        that.select(_end + 1);
                    } else {
                        that.insert('>', -1);
                    }
                    that.insert('</' + _n + '>', 1).insert(_element[1]);
                } else {
                    if ('>' === _after[0]) {
                        that.insert(' /', -1).select(_end + 3);
                    } else {
                        that.insert(' />', -1);
                    }
                }
            } else {
                if ('>' === _after[0]) {
                    that.select(_end + 1);
                } else {
                    that.insert('>', -1);
                }
                that.insert('</' + _n + '>', 1);
            }
            return that.record(), false;
        }
        if ('Enter' === key) {
            var _that$$5 = that.$(),
                _after2 = _that$$5.after,
                _before2 = _that$$5.before,
                _value = _that$$5.value,
                _lineAfter = _after2.split('\n').shift(),
                _lineBefore2 = _before2.split('\n').pop(),
                _lineMatch = _lineBefore2.match(/^(\s+)/),
                _lineMatchIndent = _lineMatch && _lineMatch[1] || "",
                _m3,
                _n2;
            var continueOnEnterTags = ['li', 'option', 'p', 'td', 'th'],
                noIndentOnEnterTags = ['script', 'style'];
            if (_m3 = _lineBefore2.match(toPattern(tagStart(tagName) + '$', ""))) {
                var _element2 = elements[_m3[1]];
                if (_element2 && false === _element2[1]) {
                    return that.insert('\n' + _lineMatchIndent, -1).record(), false;
                }
            }
            if (_after2 && _before2) {
                for (var i = 0, j = toCount(continueOnEnterTags); i < j; ++i) {
                    _n2 = continueOnEnterTags[i];
                    if (toPattern('^' + tagEnd(_n2), "").test(_lineAfter) && (_m3 = _lineBefore2.match(toPattern('^\\s*' + tagStart(_n2), "")))) {
                        // `<foo>|</foo>`
                        if (_m3[0] === _lineBefore2) {
                            if (elements[_n2] && _value && elements[_n2][1] === _value) {
                                that.insert("").wrap('\n' + _lineMatchIndent + charIndent, '\n' + _lineMatchIndent); // Unwrap if empty!
                            } else {
                                toggle.apply(that, [_n2]);
                            }
                            return that.record(), false;
                        } // `<foo>bar|</foo>`
                        return that.insert('</' + _n2 + '>\n' + _lineMatchIndent + '<' + _n2 + (_m3[2] || "") + '>', -1).insert(elements[_n2] ? elements[_n2][1] || "" : "").record(), false;
                    }
                }
                for (var _i = 0, _j = toCount(noIndentOnEnterTags); _i < _j; ++_i) {
                    _n2 = noIndentOnEnterTags[_i];
                    if (toPattern('^' + tagEnd(_n2), "").test(_lineAfter) && toPattern(tagStart(_n2) + '$', "").test(_lineBefore2)) {
                        return that.wrap('\n' + _lineMatchIndent, '\n' + _lineMatchIndent).insert(elements[_n2] ? elements[_n2][1] || "" : "").record(), false;
                    }
                }
                for (var _i2 = 1; _i2 < 7; ++_i2) {
                    if (_lineAfter.startsWith('</' + elements['h' + _i2][0] + '>') && _lineBefore2.match(toPattern('^\\s*' + tagStart(elements['h' + _i2][0]), ""))) {
                        if (elements['h' + _i2] && _value && elements['h' + _i2][1] === _value) {
                            that.insert("").wrap('\n' + _lineMatchIndent + charIndent, '\n' + _lineMatchIndent); // Insert paragraph below!
                        } else {
                            that.insert('</' + elements['h' + _i2][0] + '>\n' + _lineMatchIndent + '<' + elements.p[0] + '>', -1).replace(toPattern('^' + tagEnd(elements['h' + _i2][0])), '</' + elements.p[0] + '>', 1).insert(elements.p[1]);
                        }
                        return that.record(), false;
                    }
                }
            }
        }
        return true;
    }
    var state = defaults;
    Object.assign(TE.prototype, that$2, that$1);
    TE.state = fromStates({}, TE.state, state$2, state$1, state);
    ['alert', 'confirm', 'prompt'].forEach(type => {
        W._.window[type] && (TE.state.source[type] = W._.window[type]);
    }); // Be sure to remove the default source type
    delete TE.state.source.type;

    function _onBlurSource(e) {
        this.K.pull();
    }

    function _onInputSource(e) {
        this.K.pull();
    }

    function _onKeyDownSource(e) {
        let editor = this.TE,
            map = this.K,
            key = e.key,
            type = editor.state.source.type,
            command,
            value;
        offEventPropagation(e);
        map.push(key);
        if (command = map.test()) {
            value = map.fire(command);
            if (false === value) {
                offEventDefault(e);
            } else if (null === value) {
                console.error('Unknown command:', command);
            }
        } else {
            if ('HTML' === type) {
                if (canKeyDown(map, editor) && canKeyDown$1(map, editor) && canKeyDown$2(map, editor) && canKeyDownDent(map, editor) && canKeyDownEnter(map, editor) && canKeyDownHistory(map, editor) && canKeyDownMove(map, editor));
                else {
                    offEventDefault(e);
                }
                return;
            }
            if ('XML' === type) {
                if (canKeyDown$1(map, editor) && canKeyDown$2(map, editor) && canKeyDownDent(map, editor) && canKeyDownEnter(map, editor) && canKeyDownHistory(map, editor) && canKeyDownMove(map, editor));
                else {
                    offEventDefault(e);
                }
                return;
            } // Default
            if (canKeyDown$2(map, editor) && canKeyDownDent(map, editor) && canKeyDownEnter(map, editor) && canKeyDownHistory(map, editor) && canKeyDownMove(map, editor));
            else {
                offEventDefault(e);
            }
        }
    }

    function _onKeyUpSource(e) {
        let editor = this.TE,
            map = this.K,
            key = e.key;
        canKeyUp(map, editor);
        map.pull(key);
    }

    function _onMouseDownSource(e) {
        let editor = this.TE,
            map = this.K;
        canMouseDown(map, editor);
    }

    function _letEditorSource(self) {
        offEvent('blur', self, _onBlurSource);
        offEvent('input', self, _onInputSource);
        offEvent('keydown', self, _onKeyDownSource);
        offEvent('keyup', self, _onKeyUpSource);
        offEvent('mousedown', self, _onMouseDownSource);
        offEvent('touchstart', self, _onMouseDownSource);
    }

    function _setEditorSource(self) {
        onEvent('blur', self, _onBlurSource);
        onEvent('input', self, _onInputSource);
        onEvent('keydown', self, _onKeyDownSource);
        onEvent('keyup', self, _onKeyUpSource);
        onEvent('mousedown', self, _onMouseDownSource);
        onEvent('touchstart', self, _onMouseDownSource);
        self.TE.record();
    }

    function onChange() {
        // Destroy!
        let $;
        for (let key in TE.instances) {
            $ = TE.instances[key];
            $.loss().pop();
            delete $.self.K;
            delete TE.instances[key];
            _letEditorSource($.self);
        }
        let sources = getElements('.lot\\:field.type\\:source .textarea'),
            editor,
            map,
            state,
            type;
        sources && toCount(sources) && sources.forEach(source => {
            editor = new TE(source, getDatum(source, 'state') ?? {});
            state = editor.state;
            type = state.source.type;
            map = new W.K(editor);
            if ('HTML' === type) {
                map.commands = commands;
                map.keys['Control-b'] = 'bold';
                map.keys['Control-e'] = 'code';
                map.keys['Control-h'] = 'blocks';
                map.keys['Control-i'] = 'italic';
                map.keys['Control-k'] = 'link';
                map.keys['Control-o'] = 'image';
                map.keys['Control-q'] = 'quote';
                map.keys['Control-u'] = 'underline';
            }
            state.commands = map.commands;
            state.keys = map.keys;
            source.K = map;
            _setEditorSource(source);
        });
    }
    onChange();
    W._.on('change', onChange);
    W.TE = TE;
})();