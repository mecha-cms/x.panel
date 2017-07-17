window.PANEL = window.jQuery;

(function($, win, doc) {

    var catches = {}, a, b;

    $('form').each(function() {
        a = {};
        b = '[name]:not([disabled])';
        $(this).find('button' + b + ',input' + b + ',select' + b + ',textarea' + b).each(function() {
            a[this.name] = this;
        });
        catches[this.id || this.name || Object.keys(catches).length] = a;
    });

    $.forms = {
        $: catches
    };

    $.languages = {
        $: {}
    };

})(window.PANEL, window, document);

(function($, win, doc) {

    var nav = $('nav.n'),
        parent = nav.find('li:has(ul)');
    if (!nav.length) return;
    parent.children('a').on("click", function() {
        return $(this).next().fadeToggle(100), false;
    }).on("mouseenter", function() {
        $(this).next().fadeIn(100);
    }).parent().on("mouseleave", function() {
        $(this).children('ul').fadeOut(100);
    });
    $(doc).on("click", function() {
        var ul = parent.find('a+ul:visible');
        return !!(ul && ul.fadeOut(100));
    });

})(window.PANEL, window, document);

(function($, win, doc) {

    function hash(s) {
        return '#' + s.replace('#', "").replace(/:/g, '\\:');
    }

    var w = $(win),
        tab = $('nav.t a'),
        active = tab.filter('.is-active'),
        content = $('section.t-c'), edit;
    if (!active.length) {
        active = active.first();
        active.addClass('is-active');
    }
    if (!tab.length) return;
    content.hide().filter(hash(active[0].hash)).show();
    tab.on("click", function() {
        $(this).addClass('is-active').siblings().removeClass('is-active');
        content.hide().filter(hash(this.hash)).show();
        edit = content.find('.CodeMirror').each(function() {
            w.trigger("resize");
            this.CodeMirror && this.CodeMirror.refresh && this.CodeMirror.refresh();
        });
        return false;
    });

})(window.PANEL, window, document);

(function($, win, doc) {

    var events = 'copy cut input keydown paste';

    $.f = function(a, b, c) {
        b = b || '-';
        if (c) {
            a = a.toLowerCase();
        }
        a = a.replace(/<.*?>|&(?:[a-z\d]+|#\d+|#x[a-f\d]+);/gi, "").replace(new RegExp('[^a-z\\d' + b + ']', 'gi'), b).replace(new RegExp('[' + b + ']+', 'gi'), b).replace(new RegExp('^[' + b + ']|[' + b + ']$', 'gi'), "");
        return a;
    };

    var key_i = $('[data-key-i]:not([readonly])'),
        key_o = $('[data-key-o]:not([readonly])'),
        catched;
    if (!key_i || !key_o) return;
    key_i.closest('form').on(events, '[data-key-i]', function(e) {
        var $this = $(e.target);
        if (!catched) {
            catched = key_o.filter('[data-key-o="' + $this.data('key-i') + '"]');
        }
        catched.val($.f($this.val(), '_', true));
    });

    var slug_i = $('[data-slug-i]:not([readonly])'),
        slug_o = $('[data-slug-o]:not([readonly])'),
        catched;
    if (!slug_i || !slug_o) return;
    slug_i.closest('form').on(events, '[data-slug-i]', function(e) {
        var $this = $(e.target);
        if (!catched) {
            catched = slug_o.filter('[data-slug-o="' + $this.data('slug-i') + '"]');
        }
        catched.val($.f($this.val(), '-', true));
    });

})(window.PANEL, window, document);