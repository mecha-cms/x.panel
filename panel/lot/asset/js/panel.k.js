/*! <https://github.com/tovic/key> */

(function(win) {
    function to_lower_case(s) {
        return s.toLowerCase();
    }
    var $ = function() {},
        // key maps for the deprecated `KeyboardEvent.keyCode`
        keys = {
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
        keys_a = {
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
            'alt-graph': keys[225],
            'alternate-graph': keys[225]
        }, i, j, k, l;
        // function
        for (i = 1; i < 25; ++i) {
            keys[111 + i] = 'f' + i;
        }
        // alphabet
        for (i = 65; i < 91; ++i) {
            keys[i] = to_lower_case(String.fromCharCode(i));
        }
        $.id = 'K';
        // register key(s)
        $.keys = keys;
        $.keys_a = keys_a;
    function set(e) {
        // custom `KeyboardEvent.key` for internal use
        var keys = $.keys, // refresh…
            keys_a = $.keys_a, // refresh…
            k = e.key ? to_lower_case(e.key) : keys[e.which || e.keyCode];
        if (typeof k === "object") {
            k = e.shiftKey ? (k[1] || k[0]) : k[0];
        }
        k = to_lower_case(k);
        function ret(x, y) {
            if (typeof y === "string") {
                y = e[y + 'Key'];
            }
            if (!x || x === true) {
                if (typeof y === "boolean") {
                    return y;
                }
                return k;
            }
            if (x instanceof RegExp) {
                return y && x.test(k);
            }
            if (typeof x === "object") {
                if (y) {
                    for (i = 0, j = x.length; i < j; ++i) {
                        l = to_lower_case(x[i]);
                        if ((keys_a[l] || l) === k) return true;
                    }
                }
                return false;
            }
            return x = to_lower_case(x), y && (keys_a[x] || x) === k;
        }
        return {
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
        };
    }
    $.set = function(e, id) {
        if (e) {
            return set(e);
        }
        Object.defineProperty(KeyboardEvent.prototype, id || $.id, {
            configurable: true,
            get: function() {
                return set(this);
            }
        });
        return true;
    };
    $.reset = function() {}; // TODO
    $.version = '1.0.0';
    win.K = $;
})(window);