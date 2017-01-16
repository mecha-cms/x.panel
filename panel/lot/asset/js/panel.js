/*! <https://github.com/tovic/d-o-m> */

(function(win, doc, DOM_NS) {

    var html = doc.documentElement,
        replace = 'replace',
        create = 'createElement',
        first_child = 'firstChild',
        ge = 'getElement',
        gebi = ge + 'ById',
        gebc = ge + 'sByClassName',
        gebt = ge + 'sByTagName',
        qsa = 'querySelectorAll',
        attributes = 'attributes',
        DOM,
        DOM_NS_0 = DOM_NS[0],
        DOM_NS_1 = DOM_NS[1] || DOM_NS_0,

        a, b, c, d, e, f, g, h, i, j, k, l, m, n, o, p, q, r, s, t, u, v, w, x, y, z;

    function to_lower_case(s) {
        return s.toLowerCase();
    }

    function to_upper_case(s) {
        return s.toUpperCase();
    }

    function to_array(x) {
        return Array.prototype.slice.call(x);
    }

    function object_keys(x) {
        return Object.keys(x);
    }

    function count(x) {
        return x.length;
    }

    function count_object(x) {
        return count(object_keys(x));
    }

    function type(node, s) {
        return to_lower_case(str(node)) === '[object ' + s + ']';
    }

    function is_set(x) {
        return typeof x !== "undefined";
    }

    function is_string(x) {
        return typeof x === "string";
    }

    function is_number(x) {
        return typeof x === "number";
    }

    function is_function(x) {
        return typeof x === "function";
    }

    function is_object(x) {
        return typeof x === "object";
    }

    function is_plain_object(x) {
        return is_object(x) && !is_dom(x) && type(x, 'object');
    }

    function is_array(x) {
        return x instanceof Array;
    }

    function is_boolean(x) {
        return typeof x === "boolean";
    }

    function is_pattern(x) {
        return x instanceof RegExp ? (x.source || true) : false;
    }

    function is_dom(x) {
        return x instanceof HTMLElement;
    }

    function has(a, s) {
        if (is_object(a) && !is_array(a)) {
            x = '\0';
            for (i in a) {
                if (str_join(a[i], x) === str_join(s, x)) {
                    return str(i);
                }
            }
            return -1;
        }
        return a.indexOf(s);
    }

    function edge(a, b, c) {
        if (is_set(b) && a < b) return b;
        if (is_set(c) && a > c) return c;
        return a;
    }

    function trim(s, x) {
        if (x === 0) {
            return s[replace](/^[\s\uFEFF\xA0]+/, ""); // trim left
        } else if (x === 1) {
            return s[replace](/[\s\uFEFF\xA0]+$/, ""); // trim right
        }
        return s[replace](/^[\s\uFEFF\xA0]*|[\s\uFEFF\xA0]*$/g, "") // trim left and right
    }

    function uid(s) {
        return (s || "") + Math.floor(Date.now() * Math.random());
    }

    function str_split(s, f) {
        return is_string(s) ? trim(s).split(is_set(f) ? f : /\s+/) : s;
    }

    function str_join(s, f) {
        return is_array(s) ? s.join(is_set(f) ? f : ' ') : s;
    }

    function arr(x) {
        var o = [];
        return each(x, function(v) {
            o.push(is_plain_object(v) ? arr(v) : v);
        }), o;
    }

    function arr_unique(a) {
        var o = [];
        for (i = 0, j = count(a); i < j; ++i) {
            has(o, a[i]) === -1 && o.push(a[i]);
        }
        return o;
    }

    function obj(x) {
        var o = {};
        return each(x, function(v, i) {
            if (is_array(v)) {
                o[i] = obj(v);
            } else {
                o[i] = v;
            }
        }), o;
    }

    function int(x) {
        return parseFloat(x);
    }

    function str(x, i) {
        return x.toString(is_set(i) ? i : null);
    }

    function pattern(a, b) {
        return new RegExp(a, b);
    }

    function camelize(s) {
        return s[replace](/\-([a-z])/g, function(a, b) {
            return to_upper_case(b);
        });
    }

    function pascalize(s) {
        return camelize('-' + s);
    }

    function dasherize(s) {
        return s[replace](/([A-Z])/g, function(a, b) {
            return '-' + to_lower_case(b);
        });
    }

    function css(a, b, c) {
        if (!a) return;
        var o = win.getComputedStyle(a, (c = c || null)),
            h = {}, i, j, k, l;
        if (is_object(b)) {
            if (is_array(b)) {
                o = [];
                for (i in b) {
                    if (j = b[i]) {
                        l = css(a, j, c);
                        o[i] = l;
                    }
                }
                return o;
            }
            for (i in b) {
                j = b[i];
                a.style[camelize(i[replace](/^\0/, ""))] = j === 0 ? 0 : (j ? (is_string(j) ? j : j + 'px') : "");
            }
            return a;
        } else if (b) {
            if (b[0] === '\0') {
                b = b.slice(1);
                k = 1;
            }
            i = o[camelize(b)];
            j = k ? i : int(i);
            return j === 0 ? 0 : (j || i);
        }
        return (function() {
            for (i in o) {
                j = o.getPropertyValue(i);
                if (!j) continue;
                k = int(j);
                h[dasherize(i)] = k === 0 ? 0 : (k || j || "");
            }
            return h;
        })();
    }

    function extend(a, b) {
        b = b || {};
        for (i in b) {
            if (is_plain_object(a[i]) || is_array(a[i]) && is_plain_object(b[i]) || is_array(b[i])) {
                a[i] = extend(a[i], b[i]);
            } else {
                a[i] = b[i];
            }
        }
        return a;
    }

    function each(a, fn, as_array) {
        var i, j, k;
        if (as_array || is_array(a)) {
            for (i = 0, j = count(a); i < j; ++i) {
                k = fn(a[i], i, a);
                if (k === true) {
                    continue;
                } else if (k === false) {
                    break;
                }
            }
        } else {
            for (i in a) {
                k = fn(a[i], i, a);
                if (k === true) {
                    continue;
                } else if (k === false) {
                    break;
                }
            }
        }
        return a;
    }

    function closest(a, b) {
        while ((a = dom_parent(a)) && a !== b);
        return a;
    }

    function el(em, node, attr) {
        em = em || 'div';
        em = is_string(em) ? ((em = trim(em)), em[0] === '<' && em.slice(-1) === '>' && count(em) >= 3 ? (f = doc[create]('div'), f.innerHTML = em, f[first_child]) : doc[create](em)) : em;
        if (is_plain_object(attr)) {
            if (!node) {
                if (is_set(attr.html)) {
                    node = attr.html;
                    delete attr.html;
                } else if (is_set(attr.text)) {
                    node = attr.text;
                    delete attr.text;
                }
            }
            for (i in attr) {
                v = attr[i];
                if (i === 'classes') {
                    class_set(em, v);
                } else if (i === 'data') {
                    for (j in v) {
                        v = v[j];
                        if (v === null) {
                            data_reset(em, j);
                        } else {
                            data_set(em, j, v);
                        }
                    }
                } else if (i === 'css') {
                    if (is_string(v)) {
                        attr_set(em, 'style', v);
                    } else if (v === null) {
                        attr_reset(em, 'style');
                    } else {
                        css(em, v);
                    }
                } else {
                    if (is_function(v)) {
                        em[i] = v;
                    } else {
                        if (v === null) {
                            attr_reset(em, i);
                        } else {
                            attr_set(em, i, is_array(v) ? v.join(' ') : "" + v);
                        }
                    }
                }
            }
        }
        if (is_dom(node)) {
            dom_set(em, node);
        } else if (is_object(node)) {
            for (i in node) {
                v = node[i];
                if (is_dom(v)) {
                    dom_set(em, v);
                } else {
                    if (v !== false) content_set(em, v);
                }
            }
        } else {
            if (is_set(node) && node !== false) content_set(em, node);
        }
        return em;
    }

    function encode_value(s) {
        return s === true ? 'true' : s === false ? 'false' : s === null ? 'null' : is_number(s) ? s + "" : is_plain_object(s) ? $.encode.json(s) : s;
    }

    function decode_value(s) {
        try {
            s = s === 'true' ? true : s === 'false' ? false : s === 'null' ? null : +s + "" === s ? +s : /^(?:\{[\w\W]*\}|\[[\w\W]*\]|"[\w\W]*")$/.test(s) ? $.decode.json(s) : s;
        } catch (e) {}
        return s;
    }

    var prop_contenteditable = 'contentEditable',
        prop_designmode = 'designMode',
        prop_maxlength = 'maxLength',
        prop_nodename = 'nodeName',
        prop_readonly = 'readOnly',
        prop_tabindex = 'tabIndex',
        prop_aliases = {
            'check': 'checked',
            'contenteditable': prop_contenteditable,
            'class': 'className',
            'designmode': prop_designmode,
            'disable': 'disabled',
            'for': 'htmlFor',
            'hide': 'hidden',
            'maxlength': prop_maxlength,
            'nodename': prop_nodename,
            'readonly': prop_readonly,
            'select': 'selected',
            'tabindex': prop_tabindex,
            'auto-focus': 'autofocus',
            'auto-complete': 'autocomplete',
            'content-edit': prop_contenteditable,
            'content-editable': prop_contenteditable,
            'design-mode': prop_designmode,
            'max-length': prop_maxlength,
            'node-name': prop_nodename,
            'read-only': prop_readonly,
            'spell-check': 'spellcheck',
            'tab-index': prop_tabindex
        };

    function prop(s) {
        return prop_aliases[s] || s;
    }

    function attr_set(node, a, b) {
        if (is_object(a)) {
            for (i in a) {
                attr_set(node, i, a[i]);
            }
        } else {
            node[(b === null ? 'remove' : 'set') + 'Attribute'](a, encode_value(b));
        }
    }

    function attr_get(node, a, b) {
        var o = {};
        if (!a) {
            for (i = 0, j = node[attributes], k = count(j); i < k; ++i) {
                l = j[i];
                o[l.name] = decode_value(l.value);
            }
            return count_object(o) ? (is_plain_object(b) ? extend(b, o) : o) : (is_set(b) ? b : {});
        }
        if (is_string(a)) {
            return attr_get(node, [a], [is_set(b) ? b : ""])[0];
        }
        o = [];
        for (i in a) {
            i = a[i];
            i = node.getAttribute(i) || "";
            o.push(decode_value(i));
        }
        return count(o) ? o : (is_set(b) ? b : []);
    }

    function attr_reset(node, a) {
        if (is_object(a)) {
            for (i in a) {
                attr_reset(node, a[i]);
            }
        } else {
            if (!is_set(a)) {
                attr_reset(node, 'class'); // :(
                for (i = 0, j = node[attributes], k = count(j); i < k; ++i) {
                    if (j[i]) attr_reset(node, j[i].name);
                }
            } else {
                node.removeAttribute(a);
            }
        }
    }

    function data_set(node, a, b) {
        if (is_object(a)) {
            for (i in a) {
                data_set(node, i, a[i]);
            }
        } else {
            attr_set(node, 'data-' + a, b);
        }
    }

    function data_get(node, a, b) {
        var o = {};
        if (!a) {
            for (i = 0, j = node[attributes], k = count(j); i < k; ++i) {
                l = j[i];
                m = l.name;
                if (m.slice(0, 5) === 'data-') {
                    o[m.slice(5)] = decode_value(l.value);
                }
            }
            return count_object(o) ? (is_plain_object(b) ? extend(b, o) : o) : (is_set(b) ? b : {});
        }
        if (is_string(a)) {
            return attr_get(node, ['data-' + a], [is_set(b) ? b : ""])[0];
        }
        o = [];
        for (i in a) {
            i = a[i];
            i = attr_get(node, 'data-' + i);
            o.push(i);
        }
        return count(o) ? o : (is_set(b) ? b : []);
    }

    function data_reset(node, a) {
        if (is_object(a)) {
            for (i in a) {
                attr_reset(node, 'data-' + a[i]);
            }
        } else {
            if (!is_set(a)) {
                for (i = 0, j = node[attributes], k = count(j); i < k; ++i) {
                    if ((l = j[i]) && l.name.slice(0, 5) === 'data-') {
                        attr_reset(node, l.name);
                    }
                }
            } else {
                attr_reset(node, 'data-' + a);
            }
        }
    }

    function class_set(node, s) {
        s = str_split(s);
        for (i in s) {
            node.classList.add(s[i]);
        }
    }

    function class_get(node, s, b) {
        var o = [];
        if (!s) {
            o = str_split(node.className);
            return count(o) ? o : (is_set(b) ? b : []);
        }
        if (is_string(s)) {
            return class_get(node, [s], [is_set(b) ? b : ""])[0];
        }
        for (i in s) {
            i = s[i];
            if (node.classList.contains(i)) {
                o.push(i);
            }
        }
        return count(o) ? o : (is_set(b) ? b : []);
    }

    function class_reset(node, s) {
        if (!is_set(s)) {
            attr_reset(node, 'class');
        } else {
            s = str_split(s);
            for (i in s) {
                node.classList.remove(s[i]);
            }
        }
    }

    function class_toggle(node, s) {
        s = str_split(s);
        for (i in s) {
            node.classList.toggle(s[i]);
        }
    }

    function event_exit(e) {
        if (e) e.preventDefault();
        return false;
    }

    function event_set(id, node, fn) {
        if (!node) return;
        id = str_split(id);
        for (i = 0, j = count(id); i < j; ++i) {
            node.addEventListener(id[i], fn, false);
        }
    }

    function event_reset(id, node, fn) {
        if (!node) return;
        id = str_split(id);
        for (i = 0, j = count(id); i < j; ++i) {
            node.removeEventListener(id[i], fn, false);
        }
    }

    function event_fire(id, node, data) {
        id = str_split(id);
        var has_event = 'createEvent' in doc, e;
        for (i = 0, j = count(id); i < j; ++i) {
            if (has_event) {
                e = doc.createEvent('HTMLEvents');
                e.data = data;
                e.initEvent(id[i], true, false);
                node.dispatchEvent(e);
            }
        }
    }

    function content_set(node, s) {
        node.innerHTML = s;
    }

    function content_get(node, s) {
        return node.innerHTML || (is_set(s) ? s : "");
    }

    function content_reset(node, deep) {
        if ((!is_set(deep) || deep) && (c = dom_children(node))) {
            for (i = 0, j = count(c); i < j; ++i) {
                content_reset(c[i]);
            }
        }
        content_set(node, "");
    }

    function dom_parent(node) {
        return node && node.parentNode;
    }

    function dom_children(node) {
        return node && to_array(node.children || []);
    }

    function dom_closest(node, s) {
        if (!is_set(s)) {
            return dom_parent(node);
        }
        return is_string(s) ? node.closest(s) : closest(node, s);
    }

    function dom_next(node) {
        return node && node.nextElementSibling;
    }

    function dom_previous(node) {
        return node && node.previousElementSibling;
    }

    function dom_index(node) {
        i = 0;
        while (node = dom_previous(node)) ++i;
        return i;
    }

    function dom_before(node, dom) {
        p = dom_parent(node);
        if (!p) return;
        p.insertBefore(dom, node);
    }

    function dom_after(node, dom) {
        p = dom_parent(node);
        if (!p) return;
        p.insertBefore(dom, dom_next(node));
    }

    function dom_begin(node, dom) {
        c = node[first_child];
        if (c) {
            dom_before(c, dom);
        } else {
            dom_set(node, dom);
        }
    }

    function dom_end(node, dom) {
        dom_set(node, dom);
    }

    function dom_set(node, dom) {
        node.appendChild(dom);
    }

    function dom_reset(node, deep) {
        var parent = dom_parent(node);
        if (parent) {
            if (!is_set(deep) || deep) {
                c = node[first_child];
                while (c) dom_reset(c);
            }
            parent.removeChild(node);
        }
    }

    function dom_copy(node, deep) {
        return node.cloneNode(!is_set(deep) ? true : !!deep);
    }

    function dom_replace(node, s) {
        dom_parent(node).replaceChild(s, node);
        return s;
    }

    function do_instance(a, b) {
        return new DOM(a, b);
    }

    (function($, $$) {

        $.version = '1.0.4';
        $[DOM_NS_1] = true; // just for test: `if (typeof $ === "function" && $.DOM) { … }`
        $.id = {
            e: {}, // element(s)
            f: {}, // function(s)
            h: {}  // hook(s)
        };

        $.edge = edge;
        $.el = el;
        $.extend = extend;
        $.has = has;
        $.plug = {};

        function hook_set(event, fn, id) {
            o = $.id.h;
            if (!is_set(event)) return o;
            if (!is_set(fn)) return o[event];
            if (!is_set(o[event])) o[event] = {};
            if (!is_set(id)) id = count_object(o[event]);
            return o[event][id] = fn, $;
        }

        function hook_reset(event, id) {
            o = $.id.h;
            if (!is_set(event)) return $.id.h = {}, $;
            if (!is_set(id) || !is_set(o[event])) return o[event] = {}, $;
            return delete o[event][id], $;
        }

        function hook_fire(event, a, id) {
            o = $.id.h;
            if (!is_set(o[event])) {
                return o[event] = {}, $;
            }
            if (!is_set(id)) {
                for (i in o[event]) {
                    o[event][i].apply($, a);
                }
            } else {
                if (is_set(o[event][id])) {
                    o[event][id].apply($, a);
                }
            }
            return $;
        }

        extend($.hooks = function(f) {
            o = $.id.h;
            return count_object(o) ? (is_plain_object(f) ? extend(f, o) : o) : (is_set(f) ? f : {});
        }, {
            set: hook_set,
            reset: hook_reset,
            fire: hook_fire
        });

        extend($.trim = trim, {
            before: function(s) {
                return trim(s, 0);
            },
            after: function(s) {
                return trim(s, 1);
            }
        });

        $.is = {
            a: is_array,
            b: is_boolean,
            e: is_dom,
            f: is_function,
            i: is_number,
            n: function(x) {
                return x === null;
            },
            o: is_object,
            r: is_pattern,
            s: is_string,
            x: function(x) {
                return !is_set(x);
            }
        };

        extend($.is.o, {
            o: is_plain_object
        });

        $.to = {
            a: arr,
            i: int,
            o: obj,
            r: pattern,
            s: str
        };

        extend($.to.a, {
            u: arr_unique
        });

        extend($.to.s, {
            c: camelize,
            d: dasherize,
            p: pascalize
        });

        $.encode = {
            base64: btoa,
            url: encodeURIComponent,
            json: JSON.stringify,
            data: encode_value
        };

        $.decode = {
            base64: atob,
            url: decodeURIComponent,
            json: JSON.parse,
            data: decode_value
        };

        // current script path
        s = doc.currentScript;
        $.path = ((s && s.src) || win.location.href).split('/').slice(0, -1).join('/');

        $.ready = function(fn) {
            return event_set.call(doc, "DOMContentLoaded", doc, fn), $;
        };

        $.load = function(fn) {
            return event_set.call(doc, "load", win, fn), $;
        };

        // key maps for the deprecated `KeyboardEvent.keyCode`
        var keys = {
            // control
            3: 'cancel',
            6: 'help',
            8: 'backspace',
            9: 'tab',
            12: 'clear',
            13: 'enter',
            16: 'shift',
            17: 'control',
            18: 'alt',
            19: 'pause',
            20: 'capslock', // not working on `keypress`
            27: 'escape',
            28: 'convert',
            29: 'nonconvert',
            30: 'accept',
            31: 'modechange',
            33: 'pageup',
            34: 'pagedown',
            35: 'end',
            36: 'home',
            37: 'arrowleft',
            38: 'arrowup',
            39: 'arrowright',
            40: 'arrowdown',
            41: 'select',
            42: 'print',
            43: 'execute',
            44: 'printscreen', // works only on `keyup` :(
            45: 'insert',
            46: 'delete',
            91: 'meta', // <https://bugzilla.mozilla.org/show_bug.cgi?id=1232918>
            93: 'contextmenu',
            144: 'numlock',
            145: 'scrolllock',
            181: 'volumemute',
            182: 'volumedown',
            183: 'volumeup',
            224: 'meta',
            225: 'altgraph',
            246: 'attn',
            247: 'crsel',
            248: 'exsel',
            249: 'eraseeof',
            250: 'play',
            251: 'zoomout',
            // num
            48: ['0', ')'],
            49: ['1', '!'],
            50: ['2', '@'],
            51: ['3', '#'],
            52: ['4', '$'],
            53: ['5', '%'],
            54: ['6', '^'],
            55: ['7', '&'],
            56: ['8', '*'],
            57: ['9', '('],
            // symbol
            32: ' ',
            59: [';', ':'],
            61: ['=', '+'],
            173: ['-', '_'],
            188: [',', '<'],
            190: ['.', '>'],
            191: ['/', '?'],
            192: ['`', '~'],
            219: ['[', '{'],
            220: ['\\', '|'],
            221: [']', '}'],
            222: ['\'', '"']
        },

        // key alias(es)
        keys_alias = {
            'alternate': keys[18],
            'option': keys[18],
            'ctrl': keys[17],
            'cmd': keys[17],
            'command': keys[17],
            'os': keys[224], // <https://bugzilla.mozilla.org/show_bug.cgi?id=1232918>
            'context': keys[93],
            'menu': keys[93],
            'context-menu': keys[93],
            'return': keys[13],
            'ins': keys[45],
            'del': keys[46],
            'esc': keys[27],
            'left': keys[37],
            'right': keys[39],
            'up': keys[38],
            'down': keys[40],
            'arrow-left': keys[37],
            'arrow-right': keys[39],
            'arrow-up': keys[38],
            'arrow-down': keys[40],
            'back': keys[8],
            'back-space': keys[8],
            'space': keys[32],
            'plus': keys[61][1],
            'minus': keys[173][0],
            'caps-lock': keys[20],
            'non-convert': keys[29],
            'mode-change': keys[31],
            'page-up': keys[33],
            'page-down': keys[34],
            'print-screen': keys[44],
            'num-lock': keys[144],
            'numeric-lock': keys[144],
            'scroll-lock': keys[145],
            'volume-mute': keys[181],
            'volume-down': keys[182],
            'volume-up': keys[183],
            'altgr': keys[225],
            'alt-gr': keys[225],
            'alt-graph': keys[225]
        }, i, j;

        // function
        for (i = 1; i < 25; ++i) {
            keys[111 + i] = 'f' + i;
        }

        // alphabet
        for (i = 65; i < 91; ++i) {
            keys[i] = to_lower_case(String.fromCharCode(i));
        }

        // register key(s)
        $.keys = keys;
        $.keys_alias = keys_alias;

        $.events = {
            set: event_set,
            reset: event_reset,
            fire: event_fire,
            x: event_exit
        };

        // add `KeyboardEvent.DOM` property
        $.event = function(e) {
            // custom `KeyboardEvent.key` for internal use
            var keys = $.keys, // refresh ...
                keys_alias = $.keys_alias, // refresh ...
                k = e.key ? to_lower_case(e.key) : keys[e.which || e.keyCode];
            if (is_object(k)) {
                k = e.shiftKey ? (k[1] || k[0]) : k[0];
            }
            k = to_lower_case(k || "");
            function ret(x, y) {
                if (is_string(y)) {
                    y = e[y + 'Key'];
                }
                if (!x || x === true) {
                    if (is_boolean(y)) {
                        return y;
                    }
                    return k;
                }
                if (is_pattern(x)) {
                    return y && x.test(k);
                }
                if (is_object(x)) {
                    if (y) {
                        for (i = 0, j = count(x); i < j; ++i) {
                            l = to_lower_case(x[i]);
                            if ((keys_alias[l] || l) === k) return true;
                        }
                    }
                    return false;
                }
                return x = to_lower_case(x), y && (keys_alias[x] || x) === k;
            }
            return e[DOM_NS_1] = {
                key: function(x) {
                    return ret(x, 1);
                },
                control: function(x) {
                    return ret(x, 'ctrl');
                },
                shift: function(x) {
                    return ret(x, 'shift');
                },
                option: function(x) {
                    return ret(x, 'alt');
                },
                meta: function(x) {
                    return ret(x, 'meta');
                }
            }, e;
        };

    })(win[DOM_NS_0] = win[DOM_NS_1] = function(target, scope) {

        return do_instance(target, scope);

    }, DOM = function(target, scope) {

        var $ = this,
            $$ = win[DOM_NS_1];

        function maybe_from_instance(s) {
            return is_array(s) && s.query && s.id ? s[0] : el(s);
        }

        function query(target, scope) {
            if (target instanceof DOM) return target;
            var head = doc.head,
                body = doc.body,
                target_o = target,
                scope_o = scope;
            scope = scope || doc;
            if (is_string(scope)) {
                scope = query(scope)[0];
            }
            scope = maybe_from_instance(scope);
            if (is_string(target) && count(target)) {
                target = trim(target);
                if (target === 'html') {
                    target = [html];
                } else if (target === 'head') {
                    target = [head];
                } else if (target === 'body') {
                    target = [body];
                } else if (target[0] === '<' && target.slice(-1) === '>') {
                    target = [el(target, false, scope_o)];
                } else if (/^[#.]?(?:\\.|[\w-]|[^\x00-\xa0])+$/.test(target)) {
                    if (target[0] === '#' && (e = scope[gebi](target.slice(1)))) {
                        target = [e];
                    } else if ((target[0] === '.' && count(e = scope[gebc](target.slice(1)))) || count(e = scope[gebt](target))) {
                        target = e;
                    } else {
                        target = [];
                    }
                } else {
                    target = scope[qsa](target);
                }
            } else if (is_dom(target)) {
                target = [target];
            } else if (!target) {
                target = [];
            } else if (target === win) {
                target = [win];
            } else if (target === doc) {
                target = [doc];
            }
            target = arr_unique(to_array(target));
            target.query = [target_o, scope_o || null];
            return target;
        }

        target = query(target);
        if ((i = has($$.id.e, target.query)) !== -1) {
            target.id = i;
        } else {
            i = uid(to_lower_case(DOM_NS_1) + ':');
            target.id = i;
            $$.id.e[i] = target.query;
        }

        function do_fire_input(v) {
            ('onchange' in v) && event_fire("change", v);
            ('oninput' in v) && event_fire("input", v);
        }

        target[DOM_NS_0] = target[DOM_NS_1] = function(a, b) {
            return do_instance(target.concat(query(a, b)));
        };

        extend(target, {
            item: function(i, f) {
                o = to_array(target);
                return is_set(i) ? (o[i] || (is_set(f) ? f : false)) : (count(o) ? o : (is_set(f) ? f : []));
            },
            each: function(fn) {
                return each(target, function(v, k, a) {
                    fn.call(v, k, a);
                }, 1);
            },
            is: function(s, f) {
                if (!is_set(s)) return target;
                f = is_set(f) ? f : [];
                if (is_function(s)) {
                    var o = [];
                    each(target, function(v, i, a) {
                        s.call(v, i, a) && o.push(v);
                    }, 1);
                    return do_instance(count(o) ? o : f);
                }
                var o = [];
                each(target, function(v) {
                    a = dom_parent(v);
                    b = query(s, a);
                    count(b) && (o = o.concat(b));
                }, 1);
                return do_instance(o || f);
            },
            not: function(s, f) {
                if (!is_set(s)) return do_instance([]);
                f = is_set(f) ? f : [];
                if (is_function(s)) {
                    var o = [];
                    each(target, function(v, i, a) {
                        !s.call(v, i, a) && o.push(v);
                    }, 1);
                    return do_instance(count(o) ? o : f);
                }
                var o = [];
                each(target, function(v) {
                    a = dom_parent(v);
                    b = query(':not(' + s + ')', a);
                    count(b) && (o = o.concat(b));
                }, 1);
                return do_instance(b || f);
            },
            has: function(s) {
                if (!count(target.children())) return false;
                return target.is(function() {
                    return count(do_instance(this).find(s)) > 0;
                });
            },
            range: function(a, b) {
                return do_instance(target.slice(a, b));
            },
            set: function(a, b, x) {
                t = is_function(b);
                if (is_object(a)) {
                    for (i in a) {
                        target.set(i, a, x);
                    }
                    return target;
                }
                return each(target, function(v, k, s) {
                    v[prop(a)] = t ? b.call(v, k, s) : b;
                    !x && do_fire_input(v);
                }, 1);
            },
            reset: function(a, x) {
                if (is_array(a)) {
                    for (i = 0, j = count(a); i < j; ++i) {
                        target.reset(a[i], x);
                    }
                    return target;
                }
                return each(target, function(v) {
                    delete v[prop(a)];
                    !x && do_fire_input(v);
                }, 1);
            },
            get: function(a, b) {
                if (is_array(a)) {
                    var o = [];
                    for (i = 0, j = count(a); i < j; ++i) {
                        o.push(target.get(a[i]));
                    }
                    return count(o) ? o : (is_set(b) ? b : []);
                }
                a = prop(a);
                return is_set(target[0][a]) ? target[0][a] : (is_set(b) ? b : false);
            },
            attributes: function(f) {
                var o = [];
                each(target, function(v) {
                    o = o.concat(attr_get(v, 0, []));
                }, 1);
                return count(o) ? o : (is_set(f) ? f : []);
            },
            classes: function(f) {
                var o = [];
                each(target, function(v) {
                    o = o.concat(class_get(v, 0, []));
                }, 1);
                return count(o) ? o : (is_set(f) ? f : []);
            },
            data: function(f) {
                var o = [];
                each(target, function(v) {
                    o = o.concat(data_get(v, 0, []));
                }, 1);
                return count(o) ? o : (is_set(f) ? f : []);
            },
            traverse: function(s) {
                var o = [];
                t = is_function(s);
                each(target, function(v, i) {
                    (t && (f = s.call(v, i)) || (f = v[s])) && o.push(f);
                }, 1);
                return do_instance(o);
            },
            index: function(i) {
                if (is_set(i)) {
                    return do_instance(target[i]);
                }
                return dom_index(target[0]);
            },
            first: function() {
                return do_instance(target[0]);
            },
            last: function() {
                return do_instance(target.pop());
            },
            parent: function() {
                return target.traverse(function() {
                    return dom_parent(this);
                });
            },
            children: function(s) {
                var o = [];
                each(target, function(v) {
                    count(v = dom_children(v)) && (o = o.concat(v));
                }, 1);
                return do_instance(o).is(s);
            },
            kin: function(s) {
                var o = [];
                each(target, function(v) {
                    each(do_instance(v).parent().children(s), function(w) {
                        w !== v && o.push(w);
                    }, 1);
                }, 1);
                return do_instance(o);
            },
            closest: function(s) {
                var o = [];
                each(target, function(v) {
                    (t = dom_closest(v, s)) && o.push(t);
                }, 1);
                return do_instance(o);
            },
            find: function(s) {
                var o = [];
                each(target, function(v) {
                    o = o.concat(query(is_set(s) ? s : '*', v));
                }, 1);
                return do_instance(o);
            },
            next: function(s) {
                return target.traverse(function() {
                    return dom_next(this);
                }).is(s);
            },
            previous: function(s) {
                return target.traverse(function() {
                    return dom_previous(this);
                }).is(s);
            },
            html: function(s) {
                if (!is_set(s)) {
                    return content_get(target[0]);
                }
                t = is_function(s);
                return each(target, function(v, k, a) {
                    content_set(v, t ? s.call(v, k, a) : s);
                }, 1);
            },
            text: function(s) {
                if (!is_set(s)) {
                    return target[0].textContent;
                }
                t = is_function(s);
                return each(target, function(v, k, a) {
                    v.textContent = t ? s.call(v, k, a) : s;
                }, 1);
            },
            value: function(s) {
                if (!is_set(s)) {
                    t = target[0];
                    v = t.value;
                    if (!attr_get(t, 'value') && v === 'on') v = true;
                    return !t.disabled && decode_value(v === 0 ? 0 : (v || ""));
                }
                return target.set('value', s, 1);
            },
            copy: function(s) {
                return do_instance(dom_copy(target[0], s));
            },
            prepend: function(s) {
                return each(target, function(v) {
                    dom_begin(v, maybe_from_instance(s));
                }, 1);
            },
            append: function(s) {
                return each(target, function(v) {
                    dom_end(v, maybe_from_instance(s));
                }, 1);
            },
            before: function(s) {
                return each(target, function(v) {
                    dom_before(v, maybe_from_instance(s));
                }, 1);
            },
            after: function(s) {
                return each(target, function(v) {
                    dom_after(v, maybe_from_instance(s));
                }, 1);
            },
            remove: function() {
                return each(target, function(v) {
                    dom_reset(v);
                }, 1);
            },
            wrap: function(s) {
                return each(target, function(v) {
                    t = query(s)[0];
                    dom_before(v, t);
                    dom_set(t, v);
                }, 1);
            },
            unwrap: function(s) {
                return each(target, function(v) {
                    t = is_set(s) ? do_instance(v).closest(s) : [dom_parent(v)];
                    dom_replace(t[0], v);
                }, 1);
            },
            css: function(a, b) {
                if (!is_set(a)) {
                    return css(target[0]);
                } else if (a === false) {
                    return each(target, function(v) {
                        attr_reset(v, 'style');
                    }, 1);
                }
                if (is_set(b)) {
                    o = {};
                    o[a] = b;
                } else {
                    o = a;
                }
                if (is_string(o) || is_array(o)) {
                    return css(target[0], o);
                }
                return each(target, function(v) {
                    css(v, o);
                }, 1);
            },
            offset: function(o) {
                t = target[0];
                if (!t) return {};
                x = t.offsetLeft;
                y = t.offsetTop;
                if (o) {
                    while (t = t.offsetParent) {
                        x += t.offsetLeft;
                        y += t.offsetTop;
                    }
                }
                return {
                    x: x,
                    y: y,
                    l: x, // `left` alias for `x`
                    t: y  // `top` alias for `y`
                };
            },
            size: function(o) {
                t = target[0];
                if (!t) return {};
                if (o) {
                    x = t.offsetWidth;
                    y = t.offsetHeight;
                } else {
                    o = css(t, ['width', 'height']);
                    x = o[0];
                    y = o[1];
                }
                return {
                    x: x,
                    y: y,
                    w: x, // `width` alias for `x`
                    h: y  // `height` alias for `y`
                };
            }
        });

        each(["click", "change", "focus", "blur", "select", "submit"], function(e) {
            target[e] = function(fn) {
                if (!is_set(fn)) {
                    return target.events.fire(e);
                }
                return target.events.set(e, fn);
            };
        }, 1);

        extend(target.attributes, {
            set: function(a, b) {
                t = is_function(b);
                return each(target, function(v, k, s) {
                    attr_set(v, a, t ? b.call(v, k, s) : b);
                }, 1);
            },
            reset: function(a) {
                return each(target, function(v) {
                    attr_reset(v, a);
                }, 1);
            },
            get: function(a, b) {
                return attr_get(target[0], a, b);
            }
        });

        extend(target.data, {
            set: function(a, b) {
                t = is_function(b);
                return each(target, function(v, k, s) {
                    data_set(v, a, t ? b.call(v, k, s) : b);
                }, 1);
            },
            reset: function(a) {
                return each(target, function(v) {
                    data_reset(v, a);
                }, 1);
            },
            get: function(a, b) {
                return data_get(target[0], a, b);
            }
        });

        extend(target.classes, {
            set: function(a) {
                t = is_function(a);
                return each(target, function(v, k, s) {
                    class_set(v, t ? a.call(v, k, s) : a);
                }, 1);
            },
            reset: function(a) {
                return each(target, function(v) {
                    class_reset(v, a);
                }, 1);
            },
            toggle: function(a) {
                t = is_function(a);
                return each(target, function(v, k, s) {
                    class_toggle(v, t ? a.call(v, k, s) : a);
                }, 1);
            },
            get: function(a, b) {
                return class_get(target[0], a, b);
            }
        });

        extend(target.events = function() {}, {
            set: function(event, fn) {
                var a = $$.id.f,
                    b = target.id,
                    c = a[b], d;
                if (!is_set(c)) {
                    a[b] = [];
                }
                return each(target, function(v) {
                    d = function(e) {
                        e = $$.event(e);
                        x = is_function(fn) ? fn.call(v, e) : fn;
                        if (x === false) return event_exit(e);
                    }
                    a[b].push(d);
                    event_set(event, v, d);
                }, 1);
            },
            reset: function(event, fn) {
                var a = $$.id.f,
                    b = target.id,
                    c = a[b], d;
                return each(target, function(v) {
                    // if (!fn) {
                        each(c, function(f) {
                            event_reset(event, v, f);
                        }, 1);
                    // } else {
                    //     d = function(e) {
                    //         e = $$.event(e);
                    //         x = is_function(fn) ? fn.call(v, e) : fn;
                    //         if (x === false) return event_exit(e);
                    //     }
                    //     event_reset(event, v, d);
                    // }
                }, 1), delete (fn && (i = has(c, d)) !== -1 ? a[b][i] : a[b]), target;
            },
            fire: function(event, data) {
                return each(target, function(v) {
                    event_fire(event, v, data);
                }, 1);
            },
            x: event_exit,
            capture: function(event, get, fn) {
                d = function(e) {
                    s = e.target;
                    t = query(get, this);
                    if (has(t, s) !== -1 || (u = do_instance(s)) && has(t, u = u.closest(get)[0]) !== -1) {
                        return fn.call(u || do_instance(s), e);
                    }
                };
                return target.events.set(event, d);
            }
        });

        // plugin API
        for (i in $$.plug) {
            target[i] = function() {
                return $$.plug[i].apply(target, arguments);
            };
        }

        return target;

    });

})(window, document, ['$', 'Panel']);


/**
 * Panel Specific Command(s)
 */

(function($, win, doc) {

    var catches = {}, a;

    // <http://stackoverflow.com/a/26556347/1163000>
    $.ajax = function(form, fn) {
        var url = form.action,
            xhr = new XMLHttpRequest(),
            params = [].filter.call(form.elements, function(el) {
            return $.is.x(el.checked) || el.checked;
        }).filter(function(el) {
            return !!el.name;
        }).filter(function(el) {
            return !el.disabled;
        }).map(function(el) {
            return encodeURIComponent(el.name) + '=' + encodeURIComponent(el.value);
        }).join('&');
        xhr.open('POST', url);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = fn.bind(xhr);
        xhr.send(params);
    };

    $('form').each(function() {
        a = {};
        $(this).find('button[name],input[name],select[name],textarea[name]').each(function() {
            a[this.name] = this;
        });
        catches[this.id || this.name || Object.keys(catches).length] = a;
    });

    $.Form = {
        lot: catches
    };

    $.Language = {
        lot: {}
    };

})(Panel, window, document);