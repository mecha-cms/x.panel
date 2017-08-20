window.PANEL = window.jQuery;

(function($, win, doc) {


    // @form

    var catches = {}, a, b;
    $('form').each(function() {
        a = {};
        b = '[name]:not([disabled])';
        $(this).find('button' + b + ',input' + b + ',select' + b + ',textarea' + b).each(function() {
            a[this.name] = this;
        });
        catches[this.id || this.name || Object.keys(catches).length] = a;
    });
    $.__form__ = {
        $: catches
    };


    // @nav

    var nav = $('nav.n'),
        parent = nav.find('li:has(ul)');
    if (nav.length) {
        parent.children('a').on("click.panel", function() {
            return $(this).next().fadeToggle(100), !$(this).parent().hasClass('n:+');
        }).on("mouseenter.panel", function() {
            $(this).next().fadeIn(100);
        }).parent().on("mouseleave.panel", function() {
            $(this).children('ul').fadeOut(100);
        });
        $(doc).on("click.panel", function() {
            var ul = parent.find('a+ul:visible');
            return !!(ul && ul.fadeOut(100));
        });
    }


    // @tab

    function hash(s) {
        return '#' + s.replace('#', "").replace(/:/g, '\\:');
    }
    var w = $(win),
        tab = $('nav.t a'),
        active = tab.filter('.is\\.active'),
        content = $('section.t-c'), edit;
    if (!active.length) {
        active = tab.first();
        active.addClass('is.active');
    }
    if (tab.length) {
        content.hide().filter(hash(active[0].hash)).show();
        tab.on("click.panel", function() {
            $(this).addClass('is.active').siblings().removeClass('is.active');
            content.hide().filter(hash(this.hash)).show();
            edit = content.find('.CodeMirror').each(function() {
                w.trigger("resize.panel");
                this.CodeMirror && this.CodeMirror.refresh && this.CodeMirror.refresh();
            });
            return false;
        });
    }


    // @key, @slug

    if (win.location.href.indexOf('/::s::/') !== -1) {
        var events = "copy.panel cut.panel input.panel keydown.panel paste.panel";
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
        if (key_i && key_o) {
            key_i.closest('form').on(events, '[data-key-i]', function(e) {
                var $this = $(e.target);
                if (!catched) {
                    catched = key_o.filter('[data-key-o="' + $this.data('key-i') + '"]');
                }
                catched.val($.f($this.val(), '_', true));
            });
        }
        var slug_i = $('[data-slug-i]:not([readonly])'),
            slug_o = $('[data-slug-o]:not([readonly])'),
            catched;
        if (slug_i && slug_o) {
            slug_i.closest('form').on(events, '[data-slug-i]', function(e) {
                var $this = $(e.target);
                if (!catched) {
                    catched = slug_o.filter('[data-slug-o="' + $this.data('slug-i') + '"]');
                }
                catched.val($.f($this.val(), '-', true));
            });
        }
    }

})(window.PANEL, window, document);