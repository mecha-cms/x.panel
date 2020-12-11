(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
    typeof define === 'function' && define.amd ? define(factory) :
    (global = typeof globalThis !== 'undefined' ? globalThis : global || self, global._ = factory());
}(this, (function () { 'use strict';

    const isArray = x => Array.isArray(x);
    const isDefined = x => 'undefined' !== typeof x;
    const isNull = x => null === x;
    const isSet = x => isDefined(x) && !isNull(x);

    const toArray = x => isArray(x) ? x : [x];

    const offEvent = (names, node, fn) => {
        toArray(names).forEach(name => node.removeEventListener(name, fn));
    };

    const onEvent = (names, node, fn, options = false) => {
        toArray(names).forEach(name => node.addEventListener(name, fn, options));
    };

    const hooks = {};

    function fire(event, data) {
        const $ = this;
        if (!isSet(hooks[event])) {
            return $;
        }
        hooks[event].forEach(hook => hook.apply($, data));
        return $;
    }

    function off(event, fn) {
        const $ = this;
        if (!isSet(event)) {
            return (hooks = {}), $;
        }
        if (isSet(hooks[event])) {
            if (isSet(fn)) {
                hooks[event].forEach((hook, i) => {
                    if (fn === hook) {
                        hooks[event].splice(i, 1);
                    }
                });
                // Clean-up empty hook(s)
                if (0 === hooks[event].length) {
                    delete hooks[event];
                }
            } else {
                delete hooks[event];
            }
        }
        return $;
    }

    function on(event, fn) {
        const $ = this;
        if (!isSet(hooks[event])) {
            hooks[event] = [];
        }
        if (isSet(fn)) {
            hooks[event].push(fn);
        }
        return $;
    }

    /*!
     * ==============================================================
     *  TAG PICKER 3.0.14
     * ==============================================================
     * Author: Taufik Nurrohman <https://github.com/taufik-nurrohman>
     * License: MIT
     * --------------------------------------------------------------
     */

    (function(win, doc, name) {

        var Arrow = 'Arrow',
            ArrowLeft = Arrow + 'Left',
            ArrowRight = Arrow + 'Right',
            Backspace = 'Backspace',
            Delete = 'Delete',
            Enter = 'Enter',
            Tab = 'Tab',

            appendChild = 'appendChild',
            children = 'children',
            classList = 'classList',
            createElement = 'createElement',
            ctrlKey = 'ctrlKey',
            disabled = 'disabled',
            firstChild = 'firstChild',
            forEach = 'forEach',
            getAttribute = 'getAttribute',
            indexOf = 'indexOf',
            innerHTML = 'innerHTML',
            insertBefore = 'insertBefore',
            instances = 'instances',
            key = 'key',
            keyCode = key + 'Code',
            nextSibling = 'nextSibling',
            parentNode = 'parentNode',
            previousSibling = 'previousSibling',
            readOnly = 'readOnly',
            removeAttribute = 'removeAttribute',
            removeChild = 'removeChild',
            replace = 'replace',
            setAttribute = 'setAttribute',
            shiftKey = 'shiftKey',
            textContent = 'textContent',
            toLowerCase = 'toLowerCase',

            delay = setTimeout;

        function arrayKey(a, b) {
            var i = b[indexOf](a);
            return i < 0 ? null : i;
        }

        function classLet(a, name) {
            return a[classList].remove(name);
        }

        function classSet(a, name) {
            return a[classList].add(name);
        }

        function inArray(a, b) {
            return b[indexOf](a) >= 0;
        }

        function isNumber(x) {
            return 'number' === typeof x;
        }

        function isSet(x) {
            return 'undefined' !== typeof x && null !== x;
        }

        function isString(x) {
            return 'string' === typeof x;
        }

        function nodeLet(el) {
            nodeGet(el) && el[parentNode][removeChild](el);
        }

        function nodeGet(el) {
            return el && el[parentNode];
        }

        function nodeSet(name, content, attr) {
            var el = isString(name) ? doc[createElement](name) : name, k, v;
            if (content || "" === content) {
                el[innerHTML] = content;
            }
            if (attr) {
                for (k in attr) {
                    v = attr[k];
                    if (null === v || false === v) {
                        el[removeAttribute](k);
                    } else {
                        el[setAttribute](k, v);
                    }
                }
            }
            return el;
        }

        (function($$) {

            $$.version = '3.0.14';

            $$.state = {
                'class': 'tag-picker',
                'escape': [',', 188],
                'join': ', ',
                'max': 9999,
                'min': 0,
                'x': false
            };

            // Collect all instance(s)
            $$[instances] = {};

        })(win[name] = function(source, o) {

            if (!source) return;

            var $ = this,
                $$ = win[name],
                placeholder = source.placeholder || "",
                tabindex = source[getAttribute]('tabindex'),
                hooks = {},
                state = Object.assign({}, $$.state, isString(o) ? {
                    'join': o
                } : (o || {})),
                i, j, v;

            // Already instantiated, skip!
            if (source[name]) {
                return $;
            }

            // Return new instance if `TP` was called without the `new` operator
            if (!($ instanceof $$)) {
                return new $$(source, state);
            }

            // Store tag picker instance to `TP.instances`
            $$[instances][source.id || source.name || Object.keys($$[instances]).length] = $;

            // Mark current DOM as active tag picker to prevent duplicate instance
            source[name] = 1;

            var view = nodeSet('span', 0, {
                    'class': state['class']
                }),
                tags = nodeSet('span', 0, {
                    'class': 'tags'
                }),
                editor = nodeSet('span', 0, {
                    'class': 'editor tag'
                }),
                editorInput = nodeSet('span', 0, {
                    'contenteditable': source[disabled] ? false : 'true',
                    'spellcheck': 'false',
                    'style': 'white-space:pre;'
                }),
                editorInputPlaceholder = nodeSet('span'), form;

            function hookLet(name, fn) {
                if (!isSet(name)) {
                    return (hooks = {}), $;
                }
                if (isSet(hooks[name])) {
                    if (isSet(fn)) {
                        for (var i = 0, j = hooks[name].length; i < j; ++i) {
                            if (fn === hooks[name][i]) {
                                hooks[name].splice(i, 1);
                            }
                        }
                        // Clean-up empty hook(s)
                        if (0 === j) {
                            delete hooks[name];
                        }
                    } else {
                        delete hooks[name];
                    }
                }
                return $;
            }

            function hookSet(name, fn) {
                if (!isSet(hooks[name])) {
                    hooks[name] = [];
                }
                if (isSet(fn)) {
                    hooks[name].push(fn);
                }
                return $;
            }

            function hookFire(name, lot) {
                if (!isSet(hooks[name])) {
                    return $;
                }
                for (var i = 0, j = hooks[name].length; i < j; ++i) {
                    hooks[name][i].apply($, lot);
                }
                return $;
            }

            // Default filter for the tag name
            $.f = function(v) {
                return v[toLowerCase]()[replace](/[^ a-z0-9-]/g, "");
            };

            function n(v) {
                return $.f(v)[replace](new RegExp('(' + state.escape.join('|')[replace](/\\/g, '\\\\') + ')+'), "").trim();
            }

            function onInput() {
                if (source[disabled] || source[readOnly]) {
                    return inputSet("");
                }
                var tag = n(editorInput[textContent]),
                    tags = $.tags, index;
                if (tag) {
                    if (!tagGet(tag)) {
                        tagSetNode(tag), tagSet(tag);
                        index = tags.length;
                        hookFire('change', [tag, index]);
                        hookFire('set.tag', [tag, index]);
                    } else {
                        hookFire('has.tag', [tag, arrayKey(tag, tags)]);
                    }
                    inputSet("");
                }
            }

            function onBlurInput() {
                onInput();
                classLet(view, 'focus');
                classLet(view, 'focus.input');
                hookFire('blur', [v, $.tags.length]);
            }

            function onClickInput() {
                hookFire('click', [$.tags]);
            }

            function onFocusInput() {
                classSet(view, 'focus');
                classSet(view, 'focus.input');
                hookFire('focus', [$.tags]);
            }

            function onKeyDownInput(e) {
                var escape = state.escape,
                    k = e[keyCode], // Legacy browser(s)
                    kk = e[key], // Modern browser(s)
                    isCtrl = e[ctrlKey],
                    isEnter = Enter === kk || 13 === k,
                    isShift = e[shiftKey],
                    isTab = Tab === kk || 9 === k,
                    lastTag = editor[previousSibling],
                    lengthTags = $.tags.length,
                    max = state.max,
                    vv = n(editorInput[textContent]), // Last value before delay
                    tag;
                // Set preferred key name
                if (isEnter) {
                    kk = '\n';
                } else if (isTab) {
                    kk = '\t';
                }
                // Skip `Tab` key
                if (isTab) ; else if (source[disabled] || source[readOnly]) {
                    // Submit the closest `<form>` element with `Enter` key
                    if (isEnter && source[readOnly]) {
                        trySubmit();
                    }
                    preventDefault(e);
                } else if (inArray(kk, escape) || inArray(k, escape)) {
                    if (lengthTags < max) {
                        // Add the tag name found in the tag editor
                        onInput();
                    } else {
                        inputSet("");
                        hookFire('max.tags', [max]);
                    }
                    preventDefault(e);
                // Submit the closest `<form>` element with `Enter` key
                } else if (isEnter) {
                    trySubmit(), preventDefault(e);
                } else {
                    delay(function() {
                        var text = editorInput[textContent],
                            v = n(text);
                        // Last try for buggy key detection on mobile device(s)
                        // Check for the last typed key in the tag editor
                        if (inArray(text.slice(-1), escape)) {
                            if (lengthTags < max) {
                                // Add the tag name found in the tag editor
                                onInput();
                            } else {
                                inputSet("");
                                hookFire('max.tags', [max]);
                            }
                            preventDefault(e);
                        // Escape character only, delete!
                        } else if ("" === v && !isCtrl && !isShift) {
                            if ("" === vv && (Backspace === kk || 8 === k)) {
                                tag = $.tags[lengthTags - 1];
                                classLet(view, 'focus.tag');
                                tagLetNode(tag), tagLet(tag);
                                if (lastTag) {
                                    hookFire('change', [tag, lengthTags - 1]);
                                    hookFire('let.tag', [tag, lengthTags - 1]);
                                }
                            } else if (ArrowLeft === kk || 37 === k) {
                                // Focus to the last tag
                                lastTag && lastTag.focus();
                            }
                        }
                        editorInputPlaceholder[innerHTML] = v ? "" : placeholder;
                    }, 0);
                }
            }

            function tagsSet(values) {
                // Remove …
                if (view[parentNode]) {
                    var prev;
                    while (prev = editor[previousSibling]) {
                        tagLetNode(prev.title);
                    }
                }
                $.tags = [];
                source.value = "";
                // … then add tag(s)
                values = values ? values.split(state.join) : [],
                j = state.max, v;
                for (i = 0; i < j; ++i) {
                    if (!values[i]) {
                        break;
                    }
                    if ("" !== (v = n(values[i]))) {
                        if (tagGet(v)) {
                            continue;
                        }
                        tagSetNode(v), tagSet(v);
                        hookFire('change', [v, i]);
                        hookFire('set.tag', [v, i]);
                    }
                }
            }

            function onSubmitForm(e) {
                if (source[disabled]) {
                    return;
                }
                var min = state.min;
                onInput(); // Force to add the tag name found in the tag editor
                if (min > 0 && $.tags.length < min) {
                    inputSet("", 1);
                    hookFire('min.tags', [min]);
                    preventDefault(e);
                    return;
                }
                // Do normal `submit` event
                return 1;
            }

            function onPasteInput() {
                delay(function() {
                    if (!source[disabled] && !source[readOnly]) {
                        tagsSet(editorInput[textContent]);
                    }
                    inputSet("");
                }, 0);
            }

            function onClickView(e) {
                if (e && view === e.target) {
                    editorInput.focus();
                    onClickInput();
                }
            }

            function onFocusSource() {
                editorInput.focus();
            }

            function onBlurTag() {
                var t = this,
                    tag = t.title,
                    tags = $.tags;
                classLet(view, 'focus');
                classLet(view, 'focus.tag');
                hookFire('blur.tag', [tag, arrayKey(tag, tags)]);
            }

            function onClickTag() {
                var tag = this.title,
                    tags = $.tags;
                hookFire('click.tag', [tag, arrayKey(tag, tags)]);
            }

            function onFocusTag() {
                var t = this,
                    tag = t.title,
                    tags = $.tags;
                classSet(view, 'focus');
                classSet(view, 'focus.tag');
                hookFire('focus.tag', [tag, arrayKey(tag, tags)]);
            }

            function onClickTagX(e) {
                if (!source[disabled] && !source[readOnly]) {
                    var tag = this[parentNode].title,
                        index = arrayKey(tag, $.tags);
                    tagLetNode(tag), tagLet(tag), inputSet("", 1);
                    hookFire('change', [tag, index]);
                    hookFire('click.tag', [tag, index]);
                    hookFire('let.tag', [tag, index]);
                }
                preventDefault(e);
            }

            function onKeyDownTag(e) {
                var t = this,
                    k = e[keyCode], // Legacy browser(s)
                    kk = e[key], // Modern browser(s)
                    isCtrl = e[ctrlKey],
                    isReadOnly = source[readOnly],
                    isShift = e[shiftKey],
                    previousTag = t[previousSibling],
                    nextTag = t[nextSibling];
                // Focus to the previous tag
                if (!isReadOnly && (ArrowLeft === kk || 37 === k)) {
                    previousTag && (previousTag.focus(), preventDefault(e));
                // Focus to the next tag or to the tag input
                } else if (!isReadOnly && (ArrowRight === kk || 39 === k)) {
                    nextTag && nextTag !== editor ? nextTag.focus() : inputSet("", 1);
                    preventDefault(e);
                // Remove tag with `Backspace` or `Delete` key
                } else if (
                    Backspace === kk || Delete === kk ||
                    8 === k || 46 === k
                ) {
                    if (!isReadOnly) {
                        var tag = t.title,
                            index = arrayKey(tag, $.tags);
                        classLet(view, 'focus.tag');
                        tagLetNode(tag), tagLet(tag);
                        // Focus to the previous tag or to the tag input after remove
                        if (Backspace === kk || 8 === k) {
                            previousTag ? previousTag.focus() : inputSet("", 1);
                        // Focus to the next tag or to the tag input after remove
                        } else /* if (Delete === kk || 46 === k) */ {
                            nextTag && nextTag !== editor ? nextTag.focus() : inputSet("", 1);
                        }
                        hookFire('change', [tag, index]);
                        hookFire('let.tag', [tag, index]);
                    }
                    preventDefault(e);
                }
            }

            function inputSet(v, focus) {
                editorInput[textContent] = v;
                editorInputPlaceholder[textContent] = v ? "" : placeholder;
                focus && editorInput.focus();
            } inputSet("");

            function off(el, name, fn) {
                el.removeEventListener(name, fn);
            }

            function on(el, name, fn) {
                el.addEventListener(name, fn, false);
            }

            function preventDefault(e) {
                e && e.preventDefault();
            }

            function tagGet(tag, hook) {
                var index = arrayKey(tag, $.tags);
                hook && hookFire('get.tag', [tag, index]);
                return isNumber(index) ? tag : null;
            }

            function tagLet(tag) {
                var index = arrayKey(tag, $.tags);
                if (isNumber(index) && index >= 0) {
                    $.tags.splice(index, 1);
                    source.value = $.tags.join(state.join);
                    return true;
                }
                return false;
            }

            function tagSet(tag, index) {
                if (isNumber(index)) {
                    index = index < 0 ? 0 : index;
                    $.tags.splice(index, 0, tag);
                } else {
                    $.tags.push(tag);
                }
                // Update value
                source.value = $.tags.join(state.join);
            }

            function tagSetNode(tag, index) {
                var node = nodeSet('span', 0, {
                    'class': 'tag',
                    'tabindex': source[disabled] ? false : '0',
                    'title': tag
                });
                if (state.x) {
                    var x = nodeSet('a', 0, {
                        'href': "",
                        'tabindex': '-1'
                    });
                    on(x, 'click', onClickTagX);
                    node[appendChild](x);
                }
                on(node, 'blur', onBlurTag);
                on(node, 'click', onClickTag);
                on(node, 'focus', onFocusTag);
                on(node, 'keydown', onKeyDownTag);
                if (tags[parentNode]) {
                    if (isNumber(index) && $.tags[index]) {
                        tags[insertBefore](node, tags[children][index]);
                    } else {
                        tags[insertBefore](node, editor);
                    }
                }
            }

            function tagLetNode(tag) {
                var index = arrayKey(tag, $.tags), node;
                if (isNumber(index) && index >= 0 && (node = tags.children[index])) {
                    off(node, 'blur', onBlurTag);
                    off(node, 'click', onClickTag);
                    off(node, 'focus', onFocusTag);
                    off(node, 'keydown', onKeyDownTag);
                    if (state.x) {
                        var x = node[firstChild];
                        off(x, 'click', onClickTagX);
                        nodeLet(x);
                    }
                    nodeLet(node);
                }
            }

            function trySubmit() {
                onSubmitForm() && form && form.submit();
            }

            classSet(source, state['class'] + '-source');
            source[parentNode][insertBefore](view, source[nextSibling]);
            view[appendChild](tags);
            tags[appendChild](editor);
            editor[appendChild](editorInput);
            editor[appendChild](editorInputPlaceholder);

            nodeSet(source, 0, {
                'tabindex': '-1'
            });

            // Capture the closest `<form>` element
            form = source.form;

            on(editorInput, 'blur', onBlurInput);
            on(editorInput, 'click', onClickInput);
            on(editorInput, 'focus', onFocusInput);
            on(editorInput, 'keydown', onKeyDownInput);
            on(editorInput, 'paste', onPasteInput);
            on(source, 'focus', onFocusSource);
            on(view, 'click', onClickView);

            form && on(form, 'submit', onSubmitForm);

            $.tags = [];

            tagsSet(source.value); // Fill value(s)

            $.hooks = hooks;
            $.input = editorInput;
            $.self = $.view = view;
            $.source = $.output = source;
            $.state = state;

            $.blur = function() {
                return (!source[disabled] && (editorInput.blur(), onBlurInput())), $;
            };

            $.click = function() {
                return view.click(), onClickView(), $;
            };

            $.fire = hookFire;

            $.focus = function() {
                if (!source[disabled]) {
                    editorInput.focus();
                    onFocusInput();
                }
                return $;
            };

            $.get = function(tag) {
                return source[disabled] ? null : tagGet(tag, 1);
            };

            $.let = function(tag) {
                if (!source[disabled] && !source[readOnly]) {
                    var min = state.min;
                    onInput();
                    if (min > 0 && $.tags.length < min) {
                        hookFire('min.tags', [min]);
                        return $;
                    }
                    tagLetNode(tag), tagLet(tag);
                }
                return $;
            };

            $.on = hookSet;
            $.off = hookLet;

            $.pop = function() {
                if (!source[name]) {
                    return $; // Already ejected
                }
                delete source[name];
                off(editorInput, 'blur', onBlurInput);
                off(editorInput, 'click', onClickInput);
                off(editorInput, 'focus', onFocusInput);
                off(editorInput, 'keydown', onKeyDownInput);
                off(editorInput, 'paste', onPasteInput);
                off(source, 'focus', onFocusSource);
                off(view, 'click', onClickView);
                form && off(form, 'submit', onSubmitForm);
                $.tags[forEach](tagLetNode);
                classLet(source, state['class'] + '-source');
                nodeSet(source, 0, {
                    'tabindex': tabindex
                });
                return nodeLet(view), hookFire('pop', [$.tags]);
            };

            $.set = function(tag, index) {
                if (!source[disabled] && !source[readOnly]) {
                    var max = state.max,
                        tags = $.tags;
                    if (!tagGet(tag)) {
                        if (tags.length < max) {
                            tagSetNode(tag, index), tagSet(tag, index);
                        } else {
                            hookFire('max.tags', [max]);
                        }
                    } else {
                        hookFire('has.tag', [tag, arrayKey(tag, tags)]);
                    }
                }
                return $;
            };

            $.value = function(values) {
                return (!source[disabled] && !source[readOnly] && tagsSet(values)), $;
            };

        });

    })(window, document, 'TP');

    var doc = document;
    function hook() {
      for (var k in TP.instances) {
        TP.instances[k].pop(); // Destroy!

        delete TP.instances[k];
      }

      var query = doc.querySelectorAll('.field\\:query .input');
      query.length && query.forEach(function ($) {
        var c = $.className;
        var $$ = new TP($, JSON.parse($.getAttribute('data-state') || '{}'));
        $$.view.className += ' ' + c;
      });
    }

    /*!
     * ==============================================================
     *  TEXT EDITOR 3.1.10
     * ==============================================================
     * Author: Taufik Nurrohman <https://github.com/taufik-nurrohman>
     * License: MIT
     * --------------------------------------------------------------
     */

    (function(win, doc, name) {

        var Selection = 'Selection',

            blur = 'blur',
            disabled = 'disabled',
            focus = 'focus',
            insert = 'insert',
            match = 'match',
            parentNode = 'parentNode',
            readOnly = 'readOnly',
            replace = 'replace',
            scroll = 'scroll',
            scrollLeft = scroll + 'Left',
            scrollTop = scroll + 'Top',
            select = 'select',
            selection = select + 'ion',
            selectionEnd = selection + 'End',
            selectionStart = selection + 'Start',
            substring = 'substring',

            delay = win.setTimeout,
            instances = 'instances';

        function count(x) {
            return x.length;
        }

        function isArray(x) {
            return x instanceof Array;
        }

        function isFunction(x) {
            return 'function' === typeof x;
        }

        function isPattern(x) {
            return x instanceof RegExp ? (x.source || true) : false;
        }

        function isSet(x) {
            return 'undefined' !== typeof x && null !== x;
        }

        function isString(x) {
            return 'string' === typeof x;
        }

        function esc(x) {
            if (isArray(x)) {
                var o = [], i;
                for (i in x) {
                    o[i] = esc(x[i]);
                }
                return o;
            }
            return x[replace](toPattern('[' + win[name].x[replace](/./g, '\\$&') + ']', 'g'), '\\$&');
        }

        function toPattern(a, b) {
            return isPattern(a) || new RegExp(a, b);
        }

        function trim(s, x) {
            return s['trim' + (-1 === x ? 'Left' : 1 === x ? 'Right' : "")]();
        }

        (function($$) {

            $$.version = '3.1.10';

            $$.state = {
                'tab': '\t'
            };

            $$[instances] = {};

            $$.x = '!$^*()-=+[]{}\\|:<>,./?'; // Escape character(s)

            $$.esc = esc;

            $$[Selection] = function(a, b, c) {
                var t = this, d;
                t.start = a;
                t.end = b;
                t.value = (d = c[substring](a, b));
                t.before = c[substring](0, a);
                t.after = c[substring](b);
                t.length = count(d);
                t.toString = function() {
                    return d;
                };
            };

        })(win[name] = function(source, o) {

            if (!source) return;

            var $ = this,
                $$ = win[name],
                patternAny = /^([\s\S]*?)$/, // Any character(s)

                body = doc.body,
                html = body[parentNode],
                state = Object.assign({}, $$.state, isString(o) ? {
                    'tab': o
                } : (o || {}));

            // Already instantiated, skip!
            if (source[name]) {
                return $;
            }

            // Return new instance if `TE` was called without the `new` operator
            if (!($ instanceof $$)) {
                return new $$(source, state);
            }

            // Store text editor instance to `TE.instances`
            $$[instances][source.id || source.name || count(Object.keys($$[instances]))] = $;

            function sourceValueGet() {
                return source.value[replace](/\r/g, "");
            }

            // The `<textarea>` element
            $.self = $.source = source;

            // The initial value
            $.value = sourceValueGet();

            // Get value
            $.get = function() {
                return !source[disabled] && trim(source.value) || null;
            };

            // Reset to the initial value
            $.let = function() {
                return (source.value = $.value), $;
            };

            // Set value
            $.set = function(value) {
                if (source[disabled] || source[readOnly]) {
                    return $;
                }
                return (source.value = value), $;
            };

            // Get selection
            $.$ = function() {
                var selection = new $$[Selection](source[selectionStart], source[selectionEnd], sourceValueGet());
                return selection;
            };

            $[focus] = function(mode) {
                var x, y;
                if (-1 === mode) {
                    x = y = 0; // Put caret at the start of the editor, scroll to the start of the editor
                } else if (1 === mode) {
                    x = count(sourceValueGet()); // Put caret at the end of the editor
                    y = source[scroll + 'Height']; // Scroll to the end of the editor
                }
                if (isSet(x) && isSet(y)) {
                    source[selectionStart] = source[selectionEnd] = x;
                    source[scrollTop] = y;
                }
                return source[focus](), $;
            };

            // Blur from the editor
            $[blur] = function() {
                return source[blur](), $;
            };

            // Select value
            $[select] = function() {
                if (source[disabled] || source[readOnly]) {
                    return source[focus](), $;
                }
                var arg = arguments,
                    counts = count(arg),
                    s = $.$(),
                    x, y, z;
                x = win.pageXOffset || html[scrollLeft] || body[scrollLeft];
                y = win.pageYOffset || html[scrollTop] || body[scrollTop];
                z = source[scrollTop];
                if (0 === counts) { // Restore selection with `$.select()`
                    arg[0] = s.start;
                    arg[1] = s.end;
                } else if (1 === counts) { // Move caret position with `$.select(7)`
                    if (true === arg[0]) { // Select all with `$.select(true)`
                        return source[focus](), source[select](), $;
                    }
                    arg[1] = arg[0];
                }
                source[focus]();
                // Default `$.select(7, 100)`
                source[selectionStart] = arg[0];
                source[selectionEnd] = arg[1];
                return source[scrollTop] = z, win.scroll(x, y), $;
            };

            // Match at selection
            $[match] = function(pattern, fn) {
                if (isArray(pattern)) {
                    var selection = $.$(),
                        m = [selection.before[match](pattern[0]), selection.value[match](pattern[1]), selection.after[match](pattern[2])];
                    return isFunction(fn) ? fn.call($, m[0] || [], m[1] || [], m[2] || []) : [!!m[0], !!m[1], !!m[2]];
                }
                var m = $.$().value[match](pattern);
                return isFunction(fn) ? fn.call($, m || []) : !!m;
            };

            // Replace at selection
            $[replace] = function(from, to, mode) {
                var selection = $.$(),
                    before = selection.before,
                    after = selection.after,
                    value = selection.value;
                if (-1 === mode) { // Replace before
                    before = before[replace](from, to);
                } else if (1 === mode) { // Replace after
                    after = after[replace](from, to);
                } else { // Replace value
                    value = value[replace](from, to);
                }
                return $.set(before + value + after)[select](before = count(before), before + count(value));
            };

            // Insert/replace at caret
            $[insert] = function(value, mode, clear) {
                var from = patternAny;
                if (clear) {
                    $[replace](from, ""); // Force to delete selection on insert before/after?
                }
                if (-1 === mode) { // Insert before
                    from = /$/;
                } else if (1 === mode) { // Insert after
                    from = /^/;
                }
                return $[replace](from, value, mode);
            };

            // Wrap current selection
            $.wrap = function(open, close, wrap) {
                var selection = $.$(),
                    before = selection.before,
                    after = selection.after,
                    value = selection.value;
                if (wrap) {
                    return $[replace](patternAny, open + '$1' + close);
                }
                return $.set(before + open + value + close + after)[select](before = count(before + open), before + count(value));
            };

            // Unwrap current selection
            $.peel = function(open, close, wrap) {
                var selection = $.$(),
                    before = selection.before,
                    after = selection.after,
                    value = selection.value;
                open = isPattern(open) || esc(open);
                close = isPattern(close) || esc(close);
                var openPattern = toPattern(open + '$'),
                    closePattern = toPattern('^' + close);
                if (wrap) {
                    return $[replace](toPattern('^' + open + '([\\s\\S]*?)' + close + '$'), '$1');
                }
                if (openPattern.test(before) && closePattern.test(after)) {
                    before = before[replace](openPattern, "");
                    after = after[replace](closePattern, "");
                    return $.set(before + value + after)[select](before = count(before), before + count(value));
                }
                return $[select]();
            };

            $.pull = function(by, includeEmptyLines /* = true */) {
                var selection = $.$();
                by = isSet(by) ? by : state.tab;
                by = isPattern(by) || esc(by);
                isSet(includeEmptyLines) || (includeEmptyLines = true);
                if (count(selection)) {
                    if (includeEmptyLines) {
                        return $[replace](toPattern('^' + by, 'gm'), "");
                    }
                    return $[insert](selection.value.split('\n').map(function(v) {
                        if (toPattern('^(' + by + ')*$').test(v)) {
                            return v;
                        }
                        return v[replace](toPattern('^' + by), "");
                    }).join('\n'));
                }
                return $[replace](toPattern(by + '$'), "", -1);
            };

            $.push = function(by, includeEmptyLines /* = false */) {
                var selection = $.$();
                by = isSet(by) ? by : state.tab;
                isSet(includeEmptyLines) || (includeEmptyLines = false);
                if (count(selection)) {
                    return $[replace](toPattern('^' + (includeEmptyLines ? "" : '(?!$)'), 'gm'), by);
                }
                return $[insert](by, -1);
            };

            $.trim = function(open, close, start, end, tidy) {
                if (!isSet(tidy)) {
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
                var selection = $.$(),
                    before = selection.before,
                    after = selection.after,
                    value = selection.value,
                    beforeClean = trim(before, 1),
                    afterClean = trim(after, -1);
                before = false !== open ? trim(before, 1) + (beforeClean || !tidy ? open : "") : before;
                after = false !== close ? (afterClean || !tidy ? close : "") + trim(after, -1) : after;
                if (false !== start) value = trim(value, -1);
                if (false !== end) value = trim(value, 1);
                return $.set(before + value + after)[select](before = count(before), before + count(value));
            };

            // Destructor
            $.pop = function() {
                return (delete source[name]), $;
            };

            // Return the text editor state
            $.state = state;

        });

    })(window, document, 'TE');

    /*!
     * ==============================================================
     *  TEXT EDITOR HISTORY 1.1.3
     * ==============================================================
     * Author: Taufik Nurrohman <https://github.com/taufik-nurrohman>
     * License: MIT
     * --------------------------------------------------------------
     */

    (function(win, doc, name) {

        var $ = win[name],
            _ = $.prototype,
            _history = '_history',
            _historyState = _history + 'State';

        function isSet(x) {
            return 'undefined' !== typeof x;
        }

        function toEdge(a, b) {
            if (isSet(b[0]) && a < b[0]) {
                return b[0];
            }
            if (isSet(b[1]) && a > b[1]) {
                return b[1];
            }
            return a;
        }

        _[_history] = [];
        _[_historyState] = -1;

        // Get history data
        _.history = function(index) {
            var t = this;
            if (!isSet(index)) {
                return t[_history];
            }
            return isSet(t[_history][index]) ? t[_history][index] : null;
        };

        // Save current state to history
        _.record = function(index) {
            var t = this,
                selection = t.$(),
                current = t[_history][t[_historyState]] || [],
                next = [t.self.value, selection.start, selection.end];
            if (
                next[0] === current[0] &&
                next[1] === current[1] &&
                next[2] === current[2]
            ) {
                return t; // Do not save duplicate
            }
            ++t[_historyState];
            return (t[_history][isSet(index) ? index : t[_historyState]] = next), t;
        };

        // Remove state from history
        _.loss = function(index) {
            var t = this, current;
            if (true === index) {
                t[_history] = [];
                t[_historyState] = -1;
                return [];
            }
            current = t[_history].splice(isSet(index) ? index : t[_historyState], 1);
            t[_historyState] = toEdge(t[_historyState] - 1, [-1]);
            return current;
        };

        // Undo current state
        _.undo = function() {
            var t = this, state;
            t[_historyState] = toEdge(t[_historyState] - 1, [0, t[_history].length - 1]);
            state = t[_history][t[_historyState]];
            return t.set(state[0]).select(state[1], state[2]);
        };

        // Redo previous state
        _.redo = function() {
            var t = this, state;
            t[_historyState] = toEdge(t[_historyState] + 1, [0, t[_history].length - 1]);
            state = t[_history][t[_historyState]];
            return t.set(state[0]).select(state[1], state[2]);
        };

    })(window, document, 'TE');

    /*!
     * ==============================================================
     *  TEXT EDITOR SOURCE 1.1.8
     * ==============================================================
     * Author: Taufik Nurrohman <https://github.com/taufik-nurrohman>
     * License: MIT
     * --------------------------------------------------------------
     */

    (function(win, doc, name) {

        var $$ = win[name],

            delay = win.setTimeout,
            esc = $$.esc,

            blur = 'blur',
            call = 'call',
            close = 'close',
            ctrlKey = 'ctrlKey',
            disabled = 'disabled',
            focus = 'focus',
            fromCharCode = 'fromCharCode',
            indexOf = 'indexOf',
            lastIndexOf = 'lastIndexOf',
            length = 'length',
            keydown = 'keydown',
            match = 'match',
            mousedown = 'mousedown',
            mouseup = 'mouseup',
            pull = 'pull',
            push = 'push',
            readOnly = 'readOnly',
            record = 'record',
            redo = 'redo',
            replace = 'replace',
            select = 'select',
            shiftKey = 'shiftKey',
            toLowerCase = 'toLowerCase',
            touch = 'touch',
            touchend = touch + 'end',
            touchstart = touch + 'start',
            undo = 'undo',

            $$$, prop;

        function eventLet(el, name, fn) {
            el.removeEventListener(name, fn);
        }

        function eventSet(el, name, fn) {
            el.addEventListener(name, fn, false);
        }

        function extend(a, b) {
            return Object.assign(a, b);
        }

        function offKeyDown(e) {
            e && e.preventDefault();
        }

        function toPattern(a, b) {
            return new RegExp(a, b);
        }

        $$$ = function(source, state) {

            var $ = this,
                pop = $.pop,
                canUndo = undo in $$.prototype;

            // Is the same as `parent::__construct()` in PHP
            $$[call]($, source, state);

            var plugin = 'source',
                state = $.state,
                defaults = {},
                // Is enabled by default, unless you set the `source` option to `false`
                active = !(plugin in state) || state[plugin];

            defaults[close] = {
                '`': '`',
                '(': ')',
                '{': '}',
                '[': ']',
                '"': '"',
                "'": "'",
                '<': '>'
            };

            defaults[pull] = function(e) {
                var isCtrl = e.ctrlKey,
                    key = e.key,
                    keyCode = e.keyCode;
                return isCtrl && ((key && '[' === key) || (keyCode && 219 === keyCode));
            };

            defaults[push] = function(e) {
                var isCtrl = e.ctrlKey,
                    key = e.key,
                    keyCode = e.keyCode;
                return isCtrl && ((key && ']' === key) || (keyCode && 221 === keyCode));
            };

            defaults[select] = true; // Enable smart selection?

            if (active) {
                state[plugin] = extend(defaults, true === state[plugin] ? {} : state[plugin]);
            }

            var stateScoped = state[plugin] || {},
                previousSelectionStart;

            function onTouch() {
                if (source[disabled] || source[readOnly]) {
                    return;
                }
                delay(function() {
                    var selection = $.$(),
                        from = /\W/g,
                        to = '|',
                        start = selection.before[replace](from, to)[lastIndexOf](to),
                        end = selection.after[replace](from, to)[indexOf](to),
                        value = selection.value;
                    start = start < 0 ? 0 : start + 1;
                    end = end < 0 ? selection.after[length] : end;
                    if (previousSelectionStart !== selection.start) {
                        $[select](start, selection.end + end);
                    }
                }, 0);
            }

            function onTouchEnd() {
                previousSelectionStart = $.$().start;
            }

            function onKeyDown(e) {
                if (source[disabled] || source[readOnly]) {
                    return;
                }
                var closure = stateScoped[close],
                    tab = state.tab,
                    k = e.keyCode,
                    kk = (e.key || String[fromCharCode](k))[toLowerCase](),
                    isCtrl = e[ctrlKey],
                    isEnter = 'enter' === kk || 13 === k,
                    isShift = e[shiftKey],
                    selection = $.$(),
                    before = selection.before,
                    value = selection.value,
                    after = selection.after,
                    charBefore = before.slice(-1),
                    charAfter = after.slice(0, 1),
                    lastTabs = before[match](toPattern('(?:^|\\n)(' + esc(tab) + '+).*$')),
                    tabs = lastTabs ? lastTabs[1] : "",
                    end = closure[kk];
                // Indent
                if (stateScoped[push] && stateScoped[push][call]($, e)) {
                    $[push](tab), rec(), offKeyDown(e);
                // Outdent
                } else if (stateScoped[pull] && stateScoped[pull][call]($, e)) {
                    $[pull](tab), rec(), offKeyDown(e);
                } else if (isCtrl) {
                    // Undo
                    if ('z' === kk || 90 === k) {
                        $[undo](), rec(), offKeyDown(e);
                    // Redo
                    } else if ('y' === kk || 89 === k) {
                        $[redo](), rec(), offKeyDown(e);
                    }
                } else if ('\\' !== charBefore && kk === charAfter) {
                    // Move to the next character
                    $[select](selection.end + 1), rec(), offKeyDown(e);
                } else if ('\\' !== charBefore && end) {
                    rec(), $.wrap(kk, end), rec(), offKeyDown(e);
                } else if ('backspace' === kk || 8 === k) {
                    var bracketsOpen = "",
                        bracketsClose = "";
                    for (var i in closure) {
                        bracketsOpen += i;
                        bracketsClose += closure[i];
                    }
                    bracketsOpen = '([' + esc(bracketsOpen) + '])';
                    bracketsClose = '([' + esc(bracketsClose) + '])';
                    var matchBefore = before[match](toPattern(bracketsOpen + '\\n(?:' + esc(tabs) + ')$')),
                        matchAfter = after[match](toPattern('^\\n(?:' + esc(tabs) + ')' + bracketsClose));
                    if (!value && matchBefore && matchAfter && matchAfter[1] === closure[matchBefore[1]]) {
                        // Collapse bracket(s)
                        $.trim("", ""), rec(), offKeyDown(e);
                    } else if (!value && before[match](toPattern(esc(tab) + '$'))) {
                        $[pull](tab), rec(), offKeyDown(e);
                    } else {
                        end = closure[charBefore];
                        if (end && end === charAfter) {
                            $.peel(charBefore, charAfter), offKeyDown(e);
                        }
                    }
                    rec();
                } else if ('delete' === kk || 46 === k) {
                    end = closure[charBefore];
                    if (end && end === charAfter) {
                        $.peel(charBefore, charAfter);
                        offKeyDown(e);
                    }
                    rec();
                } else if (isEnter) {
                    end = closure[charBefore];
                    if (end && end === charAfter) {
                        $.wrap('\n' + tab + tabs, '\n' + tabs)[blur]()[focus]();
                        offKeyDown(e);
                    } else if (value || tabs) {
                        $.insert('\n', -1, true)[push](tabs)[blur]()[focus]();
                        offKeyDown(e);
                    } else ;
                    rec();
                } else {
                    // Record history
                    delay(rec, 0);
                }
            }

            function rec() {
                canUndo && $[record]();
            }

            if (active) {
                eventSet(source, keydown, onKeyDown);
                if (stateScoped[select]) {
                    eventSet(source, mousedown, onTouch);
                    eventSet(source, mouseup, onTouchEnd);
                    eventSet(source, touchend, onTouchEnd);
                    eventSet(source, touchstart, onTouch);
                }
                rec(); // Initialize history
            }

            // Destructor
            $.pop = function() {
                pop && pop[call]($);
                // Remove event(s) from memory
                eventLet(source, keydown, onKeyDown);
                eventLet(source, mousedown, onTouch);
                eventLet(source, mouseup, onTouchEnd);
                eventLet(source, touchend, onTouchEnd);
                eventLet(source, touchstart, onTouch);
                // Reset history
                canUndo && $.loss(true);
                return $;
            };

            // Override
            $.state = state;

        };

        // Clone all static property from the old constructor
        for (prop in $$) {
            $$$[prop] = $$[prop];
        }

        // Clone prototype(s)
        $$$.prototype = $$.prototype;

        // Override
        win[name] = $$$;

    })(window, document, 'TE');

    var doc$1 = document;
    function hook$1() {
      for (var k in TE.instances) {
        TE.instances[k].pop(); // Destroy!

        delete TE.instances[k];
      }

      var source = doc$1.querySelectorAll('.field\\:source .textarea');
      source.length && source.forEach(function ($) {
        var $$ = new TE($, JSON.parse($.getAttribute('data-state') || '{}'));
      });
    }

    var doc$2 = document;

    function doHide(but) {
      doc$2.querySelectorAll('.lot\\:menu.is\\:enter').forEach(function ($$) {
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
      var t = this,
          menu = t.nextElementSibling;
      doHide(menu);
      setTimeout(function () {
        t.classList.toggle('is:active');
        t.parentNode.classList.toggle('is:active');
        menu.classList.toggle('is:enter');
      }, 1);
      e.preventDefault();
      e.stopPropagation();
    }

    function hook$2() {
      offEvent('click', doc$2, onClickHide);
      var dropdowns = doc$2.querySelectorAll('.has\\:menu');

      if (dropdowns.length) {
        dropdowns.forEach(function ($) {
          var menu = $.querySelector('.lot\\:menu');

          if (menu && menu.previousElementSibling) {
            onEvent('click', menu.previousElementSibling, onClickShow);
          }
        });
        onEvent('click', doc$2, onClickHide);
      }
    }

    var doc$3 = document;
    var win = window;
    function hook$3() {
      var tabs = doc$3.querySelectorAll('.lot\\:tab'),
          replaceState = ('replaceState' in win.history),
          setAction = function setAction($) {
        var href = $.href;

        while ($ && 'form' !== $.nodeName.toLowerCase()) {
          $ = $.parentNode;
        }

        $ && 'form' === $.nodeName.toLowerCase() && ($.action = href);
      };

      if (tabs.length) {
        tabs.forEach(function ($) {
          var panes = [].slice.call($.children),
              buttons = panes.shift().querySelectorAll('a');

          function onClick(e) {
            var t = this;

            if (!t.parentNode.classList.contains('has:link')) {
              if (!t.classList.contains('not:active')) {
                buttons.forEach(function ($$$) {
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

          buttons.forEach(function ($$, i) {
            $$._index = i;
            onEvent('click', $$, onClick);
          });
        });
      }
    }

    /*!
     * ==============================================================
     *  F3H 1.0.18
     * ==============================================================
     * Author: Taufik Nurrohman <https://github.com/taufik-nurrohman>
     * License: MIT
     * --------------------------------------------------------------
     */

    ((win, doc, name) => {

        let GET = 'GET',
            POST = 'POST',

            responseTypeHTML = 'document',
            responseTypeJSON = 'json',
            responseTypeTXT = 'text',

            indexOf = 'indexOf',
            replace = 'replace',
            search = 'search',
            test = 'test',

            history = win.history,
            location = win.location,
            home = '//' + location.hostname,
            html = doc.documentElement,
            head, body,
            instances = 'instances',

            scriptCurrent = doc.currentScript;

        function attributeGet(node, attr) {
            return node.getAttribute(attr);
        }

        function attributeHas(node, attr) {
            return node.hasAttribute(attr);
        }

        function attributeSet(node, attr, value) {
            return node.setAttribute(attr, value);
        }

        function contentGet(node) {
            return node.innerHTML;
        }

        // Convert appropriate data type value into their string format
        function eval0(v) {
            if (false === v) {
                return 'false';
            }
            if (null === v) {
                return 'null';
            }
            if (true === v) {
                return 'true';
            }
            return v + "";
        }

        // Evaluate string value into their appropriate data type
        function eval1(v) {
            if ('false' === v) {
                return false;
            }
            if ("" === v || 'null' === v) {
                return null;
            }
            if ('true' === v) {
                return true;
            }
            if (/^-?(\d*\.)?\d+$/[test](v)) {
                return +v;
            }
            return v;
        }

        function eventNameGet(node) {
            return isNodeForm(node) ? 'submit' : 'click';
        }

        function eventLet(node, name, fn) {
            node.removeEventListener(name, fn);
        }

        function eventSet(node, name, fn) {
            node.addEventListener(name, fn, false);
        }

        function hashGet(ref) {
            return ref.split('#')[1] || "";
        }

        function hashLet(ref) {
            return ref.split('#')[0];
        }

        // <https://stackoverflow.com/a/8831937/1163000>
        function idFrom(text) {
            let out = 0, c, i, j = text.length;
            if (0 === j) {
                return out;
            }
            for (i = 0; i < j; ++i) {
                c = text.charCodeAt(i);
                out = ((out << 5) - out) + c;
                out = out & out; // Convert to 32bit integer
            }
            // Force absolute value
            return out < 1 ? out * -1 : out;
        }

        function isFunction(x) {
            return 'function' === typeof x;
        }

        function isLinkForF3H(node) {
            let n = toCaseLower(name);
            // Exclude `<link rel="*">` tag that contains `data-f3h` or `f3h` attribute
            if (attributeHas(node, 'data-' + n) || attributeHas(node, n)) {
                return 1;
            }
            return 0;
        }

        function isNodeForm(x) {
            return 'form' === toCaseLower(x.nodeName);
        }

        function isObject(x) {
            return 'object' === typeof x;
        }

        function isSet(x) {
            return 'undefined' !== typeof x && null !== x;
        }

        function isScriptForF3H(node) {
            // Exclude this very JavaScript
            if (node.src && scriptCurrent.src === node.src) {
                return 1;
            }
            let n = toCaseLower(name);
            // Exclude JavaScript tag that contains `data-f3h` or `f3h` attribute
            if (attributeHas(node, 'data-' + n) || attributeHas(node, n)) {
                return 1;
            }
            // Exclude JavaScript that contains `F3H` instantiation
            if ((new RegExp('\\b' + name + '\\b')).test(contentGet(node) || "")) {
                return 1;
            }
            return 0;
        }

        function isStyleForF3H(node) {
            let n = toCaseLower(name);
            // Exclude CSS tag that contains `data-f3h` or `f3h` attribute
            if (attributeHas(node, 'data-' + n) || attributeHas(node, n)) {
                return 1;
            }
            return 0;
        }

        function linkGetAll(base) {
            let id, out = {}, link,
                links = nodeGetAll('link[rel=dns-prefetch],link[rel=preconnect],link[rel=prefetch],link[rel=preload],link[rel=prerender]', base);
            for (let i = 0, j = links.length; i < j; ++i) {
                if (isLinkForF3H(link = links[i])) {
                    continue;
                }
                link.id = (id = link.id || name + ':' + idFrom(attributeGet(link, 'href') || contentGet(link)));
                out[id] = nodeSave(link);
                out[id][2].href = link.href; // Use the resolved URL!
            }
            return out;
        }

        function nodeGet(selector, base) {
            return (base || doc).querySelector(selector);
        }

        function nodeGetAll(selector, base) {
            return (base || doc).querySelectorAll(selector);
        }

        function nodeInsert(node, before, base) {
            base.insertBefore(node, before && base === before.parentNode ? before : null);
        }

        function nodeLet(node) {
            if (!node) {
                return;
            }
            let parent = node.parentNode;
            parent && parent.removeChild(node);
        }

        function nodeRestore(from) {
            let node = doc.createElement(from[0]);
            node.innerHTML = from[1];
            for (let k in from[2]) {
                attributeSet(node, k, eval0(from[2][k]));
            }
            return node;
        }

        function nodeSave(node) {
            let attr = node.attributes,
                // `[name, content, attributes]`
                to = [toCaseLower(node.nodeName), contentGet(node), {}];
            for (let i = 0, j = attr.length; i < j; ++i) {
                to[2][attr[i].name] = eval1(attr[i].value);
            }
            return to;
        }

        // Ignore trailing `/` character(s) in URL
        function slashEndLet(ref) {
            return ref[replace](/\/+$/, "");
        }

        function preventDefault(e) {
            e.preventDefault();
        }

        function refGet() {
            return location.href;
        }

        function scriptGetAll(base) {
            let id, out = {}, script,
                scripts = nodeGetAll('script', base);
            for (let i = 0, j = scripts.length; i < j; ++i) {
                if (isScriptForF3H(script = scripts[i])) {
                    continue;
                }
                script.id = (id = script.id || name + ':' + idFrom(attributeGet(script, 'src') || contentGet(script)));
                out[id] = nodeSave(script);
            }
            return out;
        }

        function styleGetAll(base) {
            let id, out = {}, style,
                styles = nodeGetAll('link[rel=stylesheet],style', base);
            for (let i = 0, j = styles.length; i < j; ++i) {
                if (isStyleForF3H(style = styles[i])) {
                    continue;
                }
                style.id = (id = style.id || name + ':' + idFrom(attributeGet(style, 'href') || contentGet(style)));
                out[id] = nodeSave(style);
            }
            return out;
        }

        function targetGet(id, orName) {
            return id ? (doc.getElementById(id) || (orName ? doc.getElementsByName(id)[0] : null)) : null;
        }

        function toCaseLower(x) {
            return x.toLowerCase();
        }

        function toCaseUpper(x) {
            return x.toUpperCase();
        }

        function toHeadersAsProxy(xhr) {
            let out = {},
                headers = xhr.getAllResponseHeaders().trim().split(/[\r\n]+/),
                header, h, k, v, w;
            for (header in headers) {
                h = headers[header].split(': ');
                k = toCaseLower(h.shift());
                w = toCaseLower(v = h.join(': '));
                out[k] = eval1(v);
            }
            // Use proxy to make response header’s key to be case-insensitive
            return new Proxy(out, {
                get: (o, k) => {
                    return o[toCaseLower(k)] || null;
                },
                set: (o, k, v) => {
                    o[toCaseLower(k)] = v;
                }
            });
        }

        ($$ => {

            $$[instances] = {};

            $$.state = {
                'cache': false, // Store all response body to variable to be used later?
                'history': true,
                'is': (source, refNow) => {
                    let target = source.target,
                        // Get URL data as-is from the DOM attribute string
                        raw = attributeGet(source, 'href') || attributeGet(source, 'action') || "",
                        // Get resolved URL data from the DOM property
                        value = source.href || source.action || "";
                    if (target && '_self' !== target) {
                        return false;
                    }
                    // Exclude URL contains hash only, and any URL prefixed by `data:`, `javascript:` and `mailto:`
                    if ('#' === raw[0] || /^(data|javascript|mailto):/[test](raw)) {
                        return false;
                    }
                    // If `value` is the same as current URL excluding the hash, treat `raw` as hash only,
                    // so that we don’t break the native hash change event that you may want to add in the future
                    if (hashGet(value) && hashLet(refNow) === hashLet(value)) {
                        return false;
                    }
                    // Detect internal link starts from here
                    return "" === raw ||
                        0 === raw[search](/[.\/?]/) ||
                        0 === raw[indexOf](home) ||
                        0 === raw[indexOf](location.protocol + home) ||
                       -1 === raw[indexOf]('://');
                },
                'lot': {
                    'x-requested-with': name
                },
                'ref': (source, refNow) => refNow, // Default URL hook
                'sources': 'a[href],form',
                'turbo': false, // Pre-fetch any URL on hover?
                'type': responseTypeHTML,
                'types': {
                    "": responseTypeHTML, // Default response type for extension-less URL
                    'CSS': responseTypeTXT,
                    'JS': responseTypeTXT,
                    'JSON': responseTypeJSON
                }
            };

            $$.version = '1.0.18';

        })(win[name] = function(o) {

            let $ = this,
                $$ = win[name],
                caches = {},
                hooks = {},
                ref = refGet(), // Get current URL to be used as the default state after the last pop state
                refCurrent = ref, // Store current URL to a variable to be compared to the next URL

                requests = {},

                links,
                scripts,
                styles,

                state = Object.assign({}, $$.state, true === o ? {
                    cache: o
                } : (o || {})),
                sources = sourcesGet(state.sources),

                nodeCurrent;

            if (state.turbo) {
                state.cache = true; // Enable turbo feature will force enable cache feature
            }

            // Return new instance if `F3H` was called without the `new` operator
            if (!($ instanceof $$)) {
                return new $$(o);
            }

            // Store current instance to `F3H.instances`
            $$[instances][Object.keys($$[instances]).length] = $;

            function sourcesGet(sources, root) {
                let from = nodeGetAll(sources, root),
                    refNow = refGet();
                if (isFunction(state.is)) {
                    let to = [];
                    for (let i = 0, j = from.length; i < j; ++i) {
                        state.is.call($, from[i], refNow) && to.push(from[i]);
                    }
                    return to;
                }
                return from;
            }

            // Include submit button value to the form data ;)
            function doAppendCurrentButtonValue(node) {
                let buttonValueStorage = doc.createElement('input'),
                    buttons = nodeGetAll('[name][type=submit][value]', node);
                buttonValueStorage.type = 'hidden';
                nodeInsert(buttonValueStorage, 0, node);
                for (let i = 0, j = buttons.length; i < j; ++i) {
                    eventSet(buttons[i], 'click', function() {
                        buttonValueStorage.name = this.name;
                        buttonValueStorage.value = this.value;
                    });
                }
            }

            function doFetch(node, type, ref) {
                let isWindow = node === win,
                    useHistory = state.history, data;
                // Compare currently selected source element with the previously stored source element, unless it is a window.
                // Pressing back/forward button from the window shouldn’t be counted as accidental click(s) on the same source element
                if (GET === type && node === nodeCurrent && !isWindow) {
                    return; // Accidental click(s) on the same source element should cancel the request!
                }
                nodeCurrent = node; // Store currently selected source element to a variable to be compared later
                refCurrent = $.ref = ref;
                hookFire('exit', [doc, node]);
                // Get response from cache if any
                if (state.cache) {
                    let cache = caches[slashEndLet(hashLet(ref))]; // `[status, response, lot, xhrIsDocument]`
                    if (cache) {
                        $.lot = cache[2];
                        $.status = cache[0];
                        cache[3] && !isWindow && useHistory && doScrollTo(html);
                        doRefChange(ref);
                        data = [cache[1], node];
                        // Update `<link rel="*">` data for the next page
                        cache[3] && (links = doUpdateLinks(data[0]));
                        // Update CSS before markup change
                        cache[3] && (styles = doUpdateStyles(data[0]));
                        hookFire('success', data);
                        hookFire(cache[0], data);
                        sources = sourcesGet(state.sources);
                        // Update JavaScript after markup change
                        cache[3] && (scripts = doUpdateScripts(data[0]));
                        onSourcesEventsSet(data);
                        hookFire('enter', data);
                        return;
                    }
                }
                let fn, lot, redirect, status,
                    xhr = doFetchBase(node, type, ref, state.lot),
                    xhrIsDocument = responseTypeHTML === xhr.responseType,
                    xhrPush = xhr.upload;
                function dataSet() {
                    // Store response from GET request(s) to cache
                    lot = toHeadersAsProxy(xhr);
                    status = xhr.status;
                    if (GET === type && state.cache) {
                        // Make sure `status` is not `0` due to the request abortion, to prevent `null` response being cached
                        status &&
                        (caches[slashEndLet(hashLet(ref))] = [status, xhr.response, lot, xhrIsDocument]);
                    }
                    $.lot = lot;
                    $.status = status;
                }
                eventSet(xhr, 'abort', () => {
                    dataSet(), hookFire('abort', [xhr.response, node]);
                });
                eventSet(xhr, 'error', fn = () => {
                    dataSet();
                    xhrIsDocument && !isWindow && useHistory && doScrollTo(html);
                    data = [xhr.response, node];
                    // Update `<link rel="*">` data for the next page
                    xhrIsDocument && (links = doUpdateLinks(data[0]));
                    // Update CSS before markup change
                    xhrIsDocument && (styles = doUpdateStyles(data[0]));
                    hookFire('error', data);
                    sources = sourcesGet(state.sources);
                    // Update JavaScript after markup change
                    xhrIsDocument && (scripts = doUpdateScripts(data[0]));
                    onSourcesEventsSet(data);
                    hookFire('enter', data);
                });
                eventSet(xhrPush, 'error', fn);
                eventSet(xhr, 'load', fn = () => {
                    dataSet();
                    data = [xhr.response, node];
                    redirect = xhr.responseURL;
                    // Handle internal server-side redirection
                    // <https://en.wikipedia.org/wiki/URL_redirection#HTTP_status_codes_3xx>
                    if (status >= 300 && status < 400) {
                        // Redirection should delete a cache related to the response URL
                        // This is useful for case(s) like, when you have submitted a
                        // comment form and then you will be redirected to the same URL
                        let r = slashEndLet(redirect);
                        caches[r] && (delete caches[r]);
                        // Trigger hook(s) immediately
                        hookFire('success', data);
                        hookFire(status, data);
                        // Do the normal fetch
                        doFetch(nodeCurrent = win, GET, redirect || ref);
                        return;
                    }
                    // Just to be sure. Don’t worry, this wouldn’t make a duplicate history
                    // if (GET === type) {
                        doRefChange(-1 === ref[indexOf]('#') ? (redirect || ref) : ref);
                    // }
                    // Update CSS before markup change
                    xhrIsDocument && (styles = doUpdateStyles(data[0]));
                    hookFire('success', data);
                    hookFire(status, data);
                    xhrIsDocument && useHistory && doScrollTo(html);
                    sources = sourcesGet(state.sources);
                    // Update JavaScript after markup change
                    xhrIsDocument && (scripts = doUpdateScripts(data[0]));
                    onSourcesEventsSet(data);
                    hookFire('enter', data);
                });
                eventSet(xhrPush, 'load', fn);
                eventSet(xhr, 'progress', e => {
                    dataSet(), hookFire('pull', e.lengthComputable ? [e.loaded, e.total] : [0, -1]);
                });
                eventSet(xhrPush, 'progress', e => {
                    dataSet(), hookFire('push', e.lengthComputable ? [e.loaded, e.total] : [0, -1]);
                });
                return xhr;
            }

            function doFetchAbort(id) {
                if (requests[id] && requests[id][0]) {
                    requests[id][0].abort();
                    delete requests[id];
                }
            }

            function doFetchAbortAll() {
                for (let request in requests) {
                    doFetchAbort(request);
                }
            }

            // TODO: Change to the modern `window.fetch` function when it is possible to track download and upload progress!
            function doFetchBase(node, type, ref, headers) {
                ref = isFunction(state.ref) ? state.ref.call($, node, ref) : ref;
                let header, xhr = new XMLHttpRequest;
                // Automatic response type based on current file extension
                let x = toCaseUpper(ref.split(/[?&#]/)[0].split('/').pop().split('.')[1] || ""),
                    responseType = state.types[x] || state.type || responseTypeTXT;
                if (isFunction(responseType)) {
                    responseType = responseType.call($, ref);
                }
                xhr.responseType = responseType;
                xhr.open(type, ref, true);
                // if (POST === type) {
                //    xhr.setRequestHeader('content-type', node.enctype || 'multipart/form-data');
                // }
                if (isObject(headers)) {
                    for (header in headers) {
                        xhr.setRequestHeader(header, headers[header]);
                    }
                }
                xhr.send(POST === type ? new FormData(node) : null);
                return xhr;
            }

            // Focus to the first element that has `autofocus` attribute
            function doFocusToElement(data) {
                if (hooks.focus) {
                    hookFire('focus', data);
                    return;
                }
                let target = nodeGet('[autofocus]');
                target && target.focus();
            }

            // Pre-fetch page and store it into cache
            function doPreFetch(node, ref) {
                let xhr = doFetchBase(node, GET, ref), status;
                eventSet(xhr, 'load', () => {
                    if (200 === (status = xhr.status)) {
                        caches[slashEndLet(hashLet(ref))] = [status, xhr.response, toHeadersAsProxy(xhr), responseTypeHTML === xhr.responseType];
                    }
                });
            }

            function doPreFetchElement(node) {
                eventSet(node, 'mousemove', onHoverOnce);
            }

            function doRefChange(ref) {
                if (ref === refGet()) {
                    return; // Clicking on the same URL should trigger the AJAX call. Just don’t duplicate it to the history!
                }
                state.history && history.pushState({}, "", ref);
            }

            function doScrollTo(node) {
                if (!node) {
                    return;
                }
                html.scrollLeft = body.scrollLeft = node.offsetLeft;
                html.scrollTop = body.scrollTop = node.offsetTop;
            }

            // Scroll to the first element with `id` or `name` attribute that has the same value as location hash
            function doScrollToElement(data) {
                if (hooks.scroll) {
                    hookFire('scroll', data);
                    return;
                }
                doScrollTo(targetGet(hashGet(refGet()), 1));
            }

            function doUpdate(compare, to, getAll, defaultContainer) {
                let id, toCompare = getAll(compare),
                    node, placesToRestore = {}, v;
                for (id in to) {
                    if (node = nodeGet('#' + id[replace](/[:.]/g, '\\$&'))) {
                        placesToRestore[id] = node.nextElementSibling;
                    }
                    if (!toCompare[id]) {
                        delete to[id];
                        nodeLet(targetGet(id));
                    }
                }
                for (id in toCompare) {
                    if (!to[id]) {
                        to[id] = (v = toCompare[id]);
                        nodeInsert(nodeRestore(v), placesToRestore[id], defaultContainer);
                    }
                }
                return to;
            }

            function doUpdateLinks(compare) {
                return doUpdate(compare, links, linkGetAll, head);
            }

            function doUpdateScripts(compare) {
                return doUpdate(compare, scripts, scriptGetAll, body);
            }

            function doUpdateStyles(compare) {
                return doUpdate(compare, styles, styleGetAll, head);
            }

            function hookLet(name, fn) {
                if (!isSet(name)) {
                    return (hooks = {}), $;
                }
                if (isSet(hooks[name])) {
                    if (isSet(fn)) {
                        for (let i = 0, j = hooks[name].length; i < j; ++i) {
                            if (fn === hooks[name][i]) {
                                hooks[name].splice(i, 1);
                            }
                        }
                        // Clean-up empty hook(s)
                        if (0 === j) {
                            delete hooks[name];
                        }
                    } else {
                        delete hooks[name];
                    }
                }
                return $;
            }

            function hookSet(name, fn) {
                if (!isSet(hooks[name])) {
                    hooks[name] = [];
                }
                if (isSet(fn)) {
                    hooks[name].push(fn);
                }
                return $;
            }

            function hookFire(name, lot) {
                if (!isSet(hooks[name])) {
                    return $;
                }
                for (let i = 0, j = hooks[name].length; i < j; ++i) {
                    hooks[name][i].apply($, lot);
                }
                return $;
            }

            function onDocumentReady() {
                // Detect key down/up event
                eventSet(doc, 'keydown', onKeyDown);
                eventSet(doc, 'keyup', onKeyUp);
                // Set body and head variable value once, on document ready
                body = doc.body;
                head = doc.head;
                // Make sure all element(s) are captured on document ready
                $.links = links = linkGetAll();
                $.scripts = scripts = scriptGetAll();
                $.styles = styles = styleGetAll();
                onSourcesEventsSet([doc, win]);
                // Store the initial page into cache
                state.cache && doPreFetch(win, refGet());
            }

            function onFetch(e) {
                doFetchAbortAll();
                // Use native web feature when user press the control key
                if (keyIsCtrl) {
                    return;
                }
                let t = this, q,
                    href = t.href,
                    action = t.action,
                    refNow = href || action,
                    type = toCaseUpper(t.method || GET);
                if (GET === type) {
                    if (isNodeForm(t)) {
                        q = (new URLSearchParams(new FormData(t))) + "";
                        refNow = slashEndLet(refNow.split(/[?&#]/)[0]) + (q ? '?' + q : "");
                    }
                    // Immediately change the URL if turbo feature is enabled
                    if (state.turbo) {
                        doRefChange(refNow);
                    }
                }
                requests[refNow] = [doFetch(t, type, refNow), t];
                preventDefault(e);
            }

            function onHashChange(e) {
                doScrollTo(targetGet(hashGet(refGet()), 1));
                preventDefault(e);
            }

            // Pre-fetch URL on link hover
            function onHoverOnce() {
                let t = this,
                    href = t.href;
                if (!caches[slashEndLet(hashLet(href))]) {
                    doPreFetch(t, href);
                }
                eventLet(t, 'mousemove', onHoverOnce);
            }

            // Check if user is pressing the control key before clicking on a link
            let keyIsCtrl = false;

            function onKeyDown(e) {
                keyIsCtrl = e.ctrlKey;
            }

            function onKeyUp() {
                keyIsCtrl = false;
            }

            function onPopState(e) {
                doFetchAbortAll();
                let refNow = refGet();
                // Updating the hash value shouldn’t trigger the AJAX call!
                if (hashGet(refNow) && hashLet(refCurrent) === hashLet(refNow)) {
                    return;
                }
                requests[refNow] = [doFetch(win, GET, refNow), win];
            }

            function onSourcesEventsLet() {
                for (let i = 0, j = sources.length; i < j; ++i) {
                    eventLet(sources[i], eventNameGet(sources[i]), onFetch);
                }
            }

            function onSourcesEventsSet(data) {
                let turbo = state.turbo;
                for (let i = 0, j = sources.length; i < j; ++i) {
                    eventSet(sources[i], eventNameGet(sources[i]), onFetch);
                    if (isNodeForm(sources[i])) {
                        doAppendCurrentButtonValue(sources[i]);
                    } else {
                        turbo && doPreFetchElement(sources[i]);
                    }
                }
                doFocusToElement(data);
                doScrollToElement(data);
            }

            $.abort = id => {
                if (!id) {
                    doFetchAbortAll();
                } else if (requests[id]) {
                    doFetchAbort(id);
                }
                return $;
            };

            $.pop = () => {
                onSourcesEventsLet();
                eventLet(win, 'DOMContentLoaded', onDocumentReady);
                eventLet(win, 'hashchange', onHashChange);
                eventLet(doc, 'keydown', onKeyDown);
                eventLet(doc, 'keyup', onKeyUp);
                eventLet(win, 'popstate', onPopState);
                hookFire('pop', [doc, win]);
                return $.abort();
            };

            $.caches = caches;
            $.fetch = (ref, type, from) => doFetchBase(from, type, ref);
            $.fire = hookFire;
            $.hooks = hooks;
            $.links = {};
            $.lot = {};
            $.off = hookLet;
            $.on = hookSet;
            $.ref = null;
            $.scripts = {};
            $.state = state;
            $.status = null;
            $.styles = {};

            eventSet(win, 'DOMContentLoaded', onDocumentReady);

            eventSet(win, 'hashchange', onHashChange);
            eventSet(win, 'popstate', onPopState);

            return $;

        });

    })(window, document, 'F3H');

    var doc$4 = document;
    var win$1 = window;

    function querySelector(query, base) {
      return (base || doc$4).querySelector(query);
    }

    function querySelectorAll(query, base) {
      return (base || doc$4).querySelectorAll(query);
    }

    var root = doc$4.documentElement,
        f3h = null;

    if (root.classList.contains('can:fetch')) {
      // Get the default F3H element(s) filter
      var f = F3H.state.is; // Ignore navigation link(s) that has sub-menu(s) in it

      F3H.state.is = function (source, refNow) {
        return f(source, refNow) && !source.parentNode.classList.contains('has:menu');
      };

      var selectors = 'body>div,body>svg',
          elements = querySelectorAll(selectors);
      f3h = new F3H(false); // Force response type as `document`

      delete F3H.state.types.CSS;
      delete F3H.state.types.JS;
      delete F3H.state.types.JSON;
      f3h.on('error', function () {
        win$1.location.reload();
        fire('error');
      });
      f3h.on('exit', function (response, target) {
        var title = querySelector('title');

        if (title) {
          if (target && target.nodeName && 'form' === target.nodeName.toLowerCase()) {
            title.setAttribute('data-is', 'get' === target.name ? 'search' : 'push');
          } else {
            title.removeAttribute('data-is');
          }
        }

        fire('let');
      });
      f3h.on('success', function (response, target) {
        var status = f3h.status;

        if (200 === status || 404 === status) {
          var responseElements = querySelectorAll(selectors, response),
              responseRoot = response.documentElement;
          doc$4.title = response.title;
          responseRoot && root.setAttribute('class', responseRoot.getAttribute('class') + ' can:fetch');
          elements.forEach(function (element, index) {
            if (responseElements[index]) {
              element.setAttribute('class', responseElements[index].getAttribute('class'));
              element.innerHTML = responseElements[index].innerHTML;
            }
          });
          fire('change');
        }
      });
      on('change', hook$2);
      on('change', hook);
      on('change', hook$1);
      on('change', hook$3);
      on('let', function () {
        var title = querySelector('title');
        var status = title.getAttribute('data-is') || 'pull',
            titleStatus = title.getAttribute('data-is-' + status);
        titleStatus && (doc$4.title = titleStatus);
      });
    }

    onEvent('beforeload', doc$4, function () {
      return fire('let');
    });
    onEvent('load', doc$4, function () {
      return fire('get');
    });
    onEvent('DOMContentLoaded', doc$4, function () {
      return fire('set');
    });
    hook$2();
    hook();
    hook$1();
    hook$3();
    var panel = {
      f3h: f3h,
      fire: fire,
      hooks: hooks,
      off: off,
      on: on
    };

    return panel;

})));
