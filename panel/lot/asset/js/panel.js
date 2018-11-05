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

$html.addClass('js');

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
        $source.parent().addClass('active').siblings().removeClass('active');
        $target.addClass('active').siblings().removeClass('active');
        var $form = $target.closest('form');
        if (pushState) {
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
                text = '<span>' + ($this.attr('title') || $this.data('title') || '#' + id) + '</span>',
                $li = $('<li></li>'),
                $a = $('<a href="' + $href + '">' + ($i ? '<svg class="icon left" viewBox="0 0 24 24"><path d="' + $i + '"/></svg> ' + text : text) + '</a>').appendTo($li);
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

panel.nav = $navs;
panel.navs = $navs;

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
    $doc.on("click", function() {
        $navs.fire('nav:exit', [null, null]);
    });
}

var $layers = $('.layers'),
    $aLayers = $('.a-layers'); // TODO

panel.layers = $layers;
panel.aLayers = $aLayers; // TODO

if ($layers.length) {
    $layers.on('layer:change', function(e, $source, $target) {
        $target.addClass('active').siblings().removeClass('active');
        $focus = $target.find(focusable_class);
        $focus.length && $focus.focus();
    });
    $layers.each(function() {
        var $this = $(this),
            $c = $this.children('.layer'),
            $a = $this.find('.a-layer');
        $this.addClass('size-' + $c.length);
        $a.on("click", function() {
            var $source = $(this),
                $target = $(this.hash.replace(/[:]/g, '\\$&')),
                $old = $c.filter('.active');
            if ($target.length) {
                $target.fire('layer:enter', [$source, $target]);
                $target.fire('layer:exit', [$source, $old]);
                $target.fire('layer:change', [$source, $target]);
                return false;
            }
        });
    });
}

if ($aLayers.length) {
    $aLayers.each(function() {
        var $this = $(this),
            $target = $(this.hash.replace(/[:]/g, '\\$&')),
            $c = $target.children('.layer');
        if (!$target.length || $c.length < 2) {
            $this.addClass('disabled');
        }
        if (!$c.filter('.active').length) {
            $c.first().addClass('active');
        }
        $this.on("click", function() {
            var $source = $(this);
            if ($source.is('.disabled')) {
                return false;
            }
            var $old = $c.filter('.active'),
                $next = $old.next('.layer');
            if (!$next.length) {
                $next = $c.first();
            }
            $target.fire('layer:exit', [$source, $old]);
            $target.fire('layer:enter', [$source, $next]);
            $target.fire('layer:change', [$source, $next]);
            return false;
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
    $doc.on("click", function() {
        $menus.prop('hidden', true).removeClass('enter');
    });
    $menus.on('menu:enter', function(e, $source) {
        var offset = $source.offset();
        $(this).css({
            top: offset.top,
            left: offset.left
        });
    });
    $menus.each(function() {
        var $this = $(this),
            $enter = ($this.data('jsEnter') || "").replace(/[:]/g, '\\$&');
        if ($enter && ($enter = $($enter)).length) {
            $enter.on("click", function(e) {
                $this.prop('hidden', false).addClass('enter').fire('menu:enter', [$(this)]);
                e.stopPropagation();
            });
        }
    });
}


// <https://stackoverflow.com/a/18639999/1163000>
win.crc32=function(r){for(var a,o=[],c=0;c<256;c++){a=c;for(var f=0;f<8;f++)a=1&a?3988292384^a>>>1:a>>>1;o[c]=a}for(var n=-1,t=0;t<r.length;t++)n=n>>>8^o[255&(n^r.charCodeAt(t))];return(-1^n)>>>0};

// <https://stackoverflow.com/a/16861050/1163000>
win._c170e1f9 = function(url, name, width, height) { // `window.open`
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
} notify();

// Hide message with time-out
var $messages = $('.messages');
if ($messages.length) {
    $win.on("load", function() {
        setTimeout(function() {
            $messages.addClass('exit');
        }, 5000);
    });
}


})(Zepto, window.panel || {});