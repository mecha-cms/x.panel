// <https://stackoverflow.com/a/18639999/1163000>
window.crc32=function(e){for(var t,n=[],a=0;256>a;a++){t=a;for(var r=0;8>r;r++)t=1&t?3988292384^t>>>1:t>>>1;n[a]=t}for(var i=-1,o=0;o<e.length;o++)i=i>>>8^n[255&(i^e.charCodeAt(o))];return(-1^i)>>>0};

// <https://stackoverflow.com/a/16861050/1163000>
window._c170e1f9 = function(url, name, width, height) { // `window.open`
    width = width || screen.width * .8;
    height = height || screen.height * .8;
    var dualScreenLeft = 'screenLeft' in win ? win.screenLeft : win.screenX,
        dualScreenTop = 'screenTop' in win ? win.screenTop : win.screenY,
        w = win.innerWidth || html.clientWidth || screen.width,
        h = win.innerHeight || html.clientHeight || screen.height,
        left = ((w / 2) - (width / 2)) + dualScreenLeft,
        top = ((h / 2) - (height / 2)) + dualScreenTop,
        newWindow = win.open(url, name, 'menubar=no,toolbar=no,location=no,status=no,scrollbars=yes,width=' + width + ',height=' + height + ',top=' + top + ',left=' + left);
    win.focus && newWindow.focus();
    return newWindow;
};

// <https://github.com/tovic/query-string-parser>
!function(n,r){function t(n,r){function t(n){return decodeURIComponent(n)}function e(n){return void 0!==n}function i(n){return"string"==typeof n}function u(n){return i(n)&&""!==n.trim()?'""'===n||"[]"===n||"{}"===n||'"'===n[0]&&'"'===n.slice(-1)||"["===n[0]&&"]"===n.slice(-1)||"{"===n[0]&&"}"===n.slice(-1):!1}function o(n){if(i(n)){if("true"===n)return!0;if("false"===n)return!1;if("null"===n)return null;if("'"===n.slice(0,1)&&"'"===n.slice(-1))return n.slice(1,-1);if(/^-?(\d*\.)?\d+$/.test(n))return+n;if(u(n))try{return JSON.parse(n)}catch(r){}}return n}function f(n,r,t){for(var e,i=r.split("["),u=0,o=i.length;o-1>u;++u)e=i[u].replace(/\]$/,""),n=n[e]||(n[e]={});n[i[u].replace(/\]$/,"")]=t}var c={},l=n.replace(/^.*?\?/,"");return""===l?c:(l.split(/&(?:amp;)?/).forEach(function(n){var i=n.split("="),u=t(i[0]),l=e(i[1])?t(i[1]):!0;l=!e(r)||r?o(l):l,"]"===u.slice(-1)?f(c,u,l):c[u]=l}),c)}n[r]=t}(window,"q2o");

// <https://github.com/tovic/query-string-parser>
!function(n,r){function t(n,r,t,o){function u(n){return encodeURIComponent(n)}function i(n){return void 0!==n}function e(n){return null!==n&&"object"==typeof n}function f(n){return n===!0?"true":n===!1?"false":null===n?"null":e(n)?JSON.stringify(n):n+""}function c(n,r){r=r||{};for(var t in n)i(r[t])?e(n[t])&&e(r[t])&&(r[t]=c(n[t],r[t])):r[t]=n[t];return r}function l(n,r,t,o){t=t||0;var i,f,a,v=[],d=r?"%5D":"";for(i in n)f=u(i),a=n[i],e(a)&&o>t?v=c(v,l(a,r+f+d+"%5B",t+1,o)):v[r+f+d]=a;return v}t=t||1;var a,v,d=[],p=l(n,"",0,t);for(a in p)v=p[a],(v!==!1||o)&&(v=v!==!0?"="+u(f(v)):"",d.push(a+v));return d.length?"?"+d.join(r||"&"):""}n[r]=t}(window,"o2q");

(function($, panel) {

$.fn.fire = $.fn.trigger;

// Global variable(s)
var win = window,
    doc = document,
    head = doc.head,
    body = doc.body,
    html = doc.documentElement,
    $win = $(win),
    $doc = $(doc),
    $head = $(head),
    $body = $(body),
    $html = $(html),
    $script = document.currentScript,
    $token = panel.$token,
    focusable = 'a[href],button,input,select,textarea,[tabindex]',
    focusable_class = '.js\\:focus';

var $focus = $(focusable_class);
$focus.length && $focus.focus();

function query(source, key, value) {
    key = decodeURIComponent(key);
    var a = source.split('?'),
        esc = '!$^*()-=+[]{}\\|:<>,./?';
    if (!a[1]) {
        return value ? a[0] + '?' + key + '=' + value : a[0];
    }
    var parts = a[1].split('&'), data = {}, i, j;
    for (i in parts) {
        j = parts[i].split('=');
        data[decodeURIComponent(j.shift())] = j.join('=') || false;
    }
    delete data[key];
    var o = [];
    for (i in data) {
        j = data[i];
        o.push(encodeURIComponent(i) + (j !== false ? '=' + j : ""));
    }
    a[1] = o.join('&');
    value && (a[1] += '&' + key + '=' + value);
    if (a[1]) {
        if (a[1][0] === '&') {
            a[1] = a[1].slice(1);
        }
        return a[0] + '?' + a[1];
    }
    return a[0];
}

var $tabs = $('.tabs');

panel.tabs = $tabs;

if ($tabs.length) {
    var pushState = 'pushState' in win.history,
        href = win.location.href,
        action;
    $tabs.on('tab:change', function(e, $source, $target) {
        var active = $source.parent().hasClass('active');
        $source.parent().addClass('active').siblings().removeClass('active');
        $target.addClass('active').siblings().removeClass('active');
        var $form = $target.closest('form');
        if (pushState && !active) {
            if (!action) action = $form.attr('action');
            var key = $target.data('key'),
                k = 'tab[' + key[1] + ']';
            win.history.pushState({}, "", query(href, k, key[0]));
            $form.attr('action', query(action, k, key[0]));
        }
        $focus = $target.find(focusable_class);
        $focus.length && $focus.focus();
    });
    $tabs.each(function(i) {
        var $this = $(this),
            $nav = $('<nav></nav>'),
            $ul = $('<ul></ul>').appendTo($nav),
            $sections = $(this).children('section');
        if (!$sections.filter('.active').length) {
            $sections.first().addClass('active');
        }
        $this.addClass('size-' + $sections.length);
        $sections.each(function(j) {
            var $this = $(this),
                $i = $this.data('icon'),
                $href = $this.attr('href') || $this.data('href') || "",
                $target = $this.attr('target') || $this.data('target'),
                id = $this.attr('id') || j,
                title = $this.attr('title') || $this.data('title'),
                text = title || !$i ? '<span>' + (title || '#' + id) + '</span>' : "",
                $li = $('<li></li>'),
                $a = $('<a href="' + $href + '">' + ($i ? '<svg class="icon ' + (title ? 'left' : 'only') + '" viewBox="0 0 24 24"><path d="' + $i + '"/></svg> ' + text : text) + '</a>').appendTo($li);
            $this.data('key', id.split(':')[1].split('.'));
            $target && $a.attr('target', $target);
            if ($this.hasClass('active')) {
                $li.addClass('active');
            }
            $a.on("click", function(e) {
                var $source = $(this),
                    $target = $sections.eq($(this).parent().index()),
                    $old = $sections.filter('.active');
                $this.fire('tab:enter', [$source, $target]);
                $this.fire('tab:exit', [$source, $old]);
                $this.fire('tab:change', [$source, $target]);
                return $href !== "";
            });
            $li.appendTo($ul);
        }).removeAttr('title');
        $nav.prependTo($this);
    });
}

var $navs = $('.nav');

panel.navs = $navs;
panel.nav = $navs.first();

if ($navs.length) {
    $uls = $navs.find('ul ul');
    $navs.on('nav:enter', function(e, $source, $target) {
        if ($target.hasClass('enter')) {
            $target.removeClass('enter').parent().removeClass('active')
                .find('ul.enter').removeClass('enter').parent('li.active').removeClass('active');
        } else {
            $target.addClass('enter').parent().addClass('active')
                .siblings().find('ul.enter').removeClass('enter').parent('li.active').removeClass('active');
        }
        $menus.fire('menu:exit', [$source, null]);
        // console.log(['nav:enter', $source, null]);
    });
    $navs.on('nav:exit', function(e, $source, $target) {
        $uls.removeClass('enter').parent().removeClass('active');
    });
    $navs.each(function(i) {
        var $nav = $(this);
        $nav.find('li a').each(function(j) {
            var $this = $(this),
                $ul = $this.next('ul');
            if ($ul.length) {
                $this.on("click", function(e) {
                    $nav.fire('nav:enter', [$(this), $ul]);
                    e.stopPropagation();
                    return false;
                });
            }
        });
    });
}

var $slugger = $('[data-generator-key],[data-generator-slug]');

if ($slugger.length) {
    $slugger.each(function() {
        var $this = $(this), x,
            selectors = ((x = $this.data('generatorKey')) || $this.data('generatorSlug')).split(/\s+/),
            x = x ? '_' : '-';
        $.each(selectors, function() {
            var a = this.split(':'),
                $from = $this.find('[name="' + a[0] + '"]'),
                $to = $this.find('[name="' + a[1] + '"]');
            if ($from.length && $to.length) {
                $from.on("blur focus input keydown paste", function() {
                    $to.val($(this).val().replace(/[A-Z]/g, function($) {
                        return '\u001a' + $.toLowerCase();
                    }).replace(/[^a-z\d-_]/g, '\u001a').replace(/\u001a+/g, '\u001a').replace(/^\u001a|\u001a$/g, "").replace(/\u001a/g, x));
                });
            }
        });
    });
}

var $forms = $('form');

panel.forms = $forms;

if ($forms.length) {
    $forms.on("submit", function() {
        $(this).fire('form:' + (this.method.toLowerCase() === 'get' ? 'get' : 'set'));
    });
    $forms.on("reset", function() {
        $(this).fire('form:reset');
    });
}

$('.select.select-input').each(function() {
    var $this = $(this),
        $lastOption = $this.find('option').last();
    $('<option value="[...]">...</option>').appendTo($this);
    $this.on("change input", function() {
        var $t = $(this);
        if ($t.val() === '[...]') {
            $t.hide().prop('disabled', true);
            $('<input name="' + this.name + '" class="input ' + this.className + '" type="text" placeholder="' + ($lastOption.attr('value') || $lastOption.text()) + '">').removeClass('select').on("blur", function() {
                $t.show().prop('disabled', false);
                var value = $(this).val();
                if ($.trim(value) !== "") {
                    $('<option value="' + value + '">' + value + '</option>').insertBefore($t.find('option[value="[...]"]'));
                    $t.val(value);
                } else {
                    $t.val($lastOption.attr('value'));
                }
                $(this).remove();
            }).on("keydown", function(e) {
                if (e.key && /^escape|enter|arrow(up|down)$/i.test(e.key)) {
                    $(this).fire("blur");
                    $t.fire("focus");
                    e.preventDefault();
                }
            }).insertAfter($t).focus();
        }
    });
});


var $menus = $('.menus');

panel.menus = $menus;

if ($menus.length) {
    $menus.on('menu:enter', function(e, $source, $target) {
        var $this = $(this),
            offset = $source.offset(),
            width = $html.width();
        $this.removeProp('hidden').removeClass('rtl').addClass('enter').css({
            top: offset.top,
            left: offset.left
        });
        var w = $this.width();
        if (offset.left + w > width) {
            $this.addClass('rtl').css('left', width - w);
        }
        $navs.fire('nav:exit', [$source, null]);
        // console.log(['menu:enter', $source, null]);
    });
    $menus.on('menu:exit', function(e, $source) {
        $menus.prop('hidden', true).removeClass('enter').find('ul.enter').removeClass('enter').parent().removeClass('active');
    });
    $menus.each(function() {
        var $this = $(this),
            $enter = ($this.data('jsEnter') || "").replace(/[:]/g, '\\$&');
        if ($enter && ($enter = $($enter)).length) {
            $enter.on("click", function(e) {
                $this.fire('menu:enter', [$(this)]);
                e.stopPropagation();
            });
        }
    });
    $menus.find('a').on("click", function(e) {
        var $this = $(this),
            $ul = $this.next('ul');
        if ($ul.length) {
            if ($ul.hasClass('enter')) {
                $ul.removeClass('enter').parent().removeClass('active')
                    .find('ul.enter').removeClass('enter').parent('li.active').removeClass('active');
            } else {
                $ul.addClass('enter').parent().addClass('active')
                    .siblings().find('ul.enter').removeClass('enter').parent('li.active').removeClass('active');
            }
            e.stopPropagation();
            return false;
        }
    });
}


// Exit on click-outside
$doc.on("click", function() {
    $menus.fire('menu:exit', [null, null]);
    $navs.fire('nav:exit', [null, null]);
});


// Live notification(s)
var $notify = $('.nav li.li\\:message a');
function notify() {
    $.get($notify.attr('href').replace('::g::', '::a::').split(/[?&#]/)[0] + '?a=fea4a865&lot[0]=page&token=' + $token, function(i) {
        i = +i;
        // console.log(i);
        $notify
            .attr('data-i', i)
            .attr('title', i + ' ' + panel.$language[i === 1 ? 'message' : 'messages'])
            .parent()[i > 0 ? 'addClass' : 'removeClass']('current');
        $notify.find('svg path').attr('d', panel.$svg.bell[i > 0 ? 1 : 0]);
        setTimeout(notify, 10000);
    });
} $notify.length && $html.hasClass('status-1') && notify();


// Hide message with time-out
var $messages = $('.messages');
if ($messages.length) {
    $win.on("load", function() {
        setTimeout(function() {
            $messages.addClass('exit');
        }, 9000 + (1000 * $messages.children().length));
    });
}

// Apply `CodeMirror`
$codes = $('textarea.code');
if ($codes.length) {
    // Mode(s) are hard-coded :(
    var mode = 'application/x-httpd-php',
        x = panel.$url.path.split('.').pop() || 'text/plain',
        markdown = {
            name: 'markdown',
            taskLists: false,
            fencedCodeBlocks: '~~~'
        },
        yaml = {
            name: 'yaml-frontmatter',
            base: markdown
        },
        aliases = {
            'archive': yaml,
            'draft': yaml,
            'page': yaml,
            'css': 'text/x-less',
            'js': 'text/javascript'
        },
        editorConfig = {
            autoCloseBrackets: true,
            autoRefresh: true,
            lineNumbers: true,
            lineWrapping: true,
            matchBrackets: true,
            mode: mode,
            showTrailingSpace: true,
            extraKeys: {
                'Enter': 'newlineAndIndentContinueMarkdownList'
            }
        };
    x && (mode = aliases[x] || x);
    $.each($codes, function() {
        var $this = this;
        $this.editor = {'<>': $this};
        function get() {
            return this['<>'];
        }
        function reset() {
            this.editor && this.editor.$ && this.editor.$.toTextArea();
        }
        function set(config) {
            var t = this,
                name = t.name,
                m = $(t).data('syntax');
            t.editor && t.editor.$ && t.editor.$.toTextArea();
            if (m) {
                mode = m;
            } else {
                if (/(data|page)\[content\]/.test(name)) {
                    mode = markdown;
                }
                if (/(data|page)\[css\]/.test(name)) {
                    mode = t.value.search('</style>') !== -1 ? 'application/x-httpd-php' : 'text/x-less';
                } else if (/(data|page)\[js\]/.test(name)) {
                    mode = t.value.search('</script>') !== -1 ? 'application/x-httpd-php' : 'text/javascript';
                }
            }
            editorConfig.mode = mode;
            t.editor.$ = CodeMirror.fromTextArea(t, $.extend({}, editorConfig, config));
            var view = t.editor.$.getWrapperElement();
            // Update `<textarea>` value on every “blur” event
            t.editor.$.on("blur", function($) {
                 $.save();
            });
            view.className += ' ' + t.className;
            if (t.readOnly) {
                t.editor.$.setOption('readOnly', true);
                view.setAttribute('readonly', 'readonly'); // Hacky :p
            }
            $win.trigger('resize'); // Hacky :(
            return t.editor.$;
        }
        // Trigger once the editor creator
        set.call($this);
        $this.editor.get = get;
        // Add generic editor destroyer for this editor to be used by other editor(s) that want to override this editor
        $this.editor.reset = function() {
            reset.call(this['<>']);
        };
        // Add generic editor creator for this editor
        $this.editor.set = function(config) {
            return set.call(this['<>'], config);
        };
    });
}


})(Zepto, window.panel || {});