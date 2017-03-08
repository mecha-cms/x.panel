window.Panel = window.jQuery;

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
        lot: catches
    };

    $.languages = {
        lot: {}
    };

})(Panel, window, document);

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

    var key_i = $('[data-key-i]'),
        key_o = $('[data-key-o]'),
        catched;
    if (!key_i || !key_o) return;
    key_i.closest('form').on(events, '[data-key-i]', function(e) {
        var $this = $(e.target);
        if (!catched) {
            catched = key_o.filter('[data-key-o="' + $this.data('key-i') + '"]');
        }
        catched.val($.f($this.val(), '_', true));
    });

    var slug_i = $('[data-slug-i]'),
        slug_o = $('[data-slug-o]'),
        catched;
    if (!slug_i || !slug_o) return;
    slug_i.closest('form').on(events, '[data-slug-i]', function(e) {
        var $this = $(e.target);
        if (!catched) {
            catched = slug_o.filter('[data-slug-o="' + $this.data('slug-i') + '"]');
        }
        catched.val($.f($this.val(), '-', true));
    });

})(Panel, window, document);