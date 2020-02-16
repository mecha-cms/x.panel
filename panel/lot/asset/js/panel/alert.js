(function(win, doc, _) {
    function onChange() {
        var bell = doc.querySelector('.js\\:alert');
        if (bell) {
            var a = bell.querySelector('a'),
                svg = a.querySelector('use'),
                icon = svg.getAttribute('href'),
                url = a.href;
            url = url.replace(/\/::\w+::\/.*$/, '/::f::/affd6ded?token=' + _.token);
            function count() {
                fetch(url).then(function(request) {
                    return request.text();
                }).then(function(i) {
                    i = i ? +i : 0;
                    a.title = i;
                    svg.setAttribute('href', i ? '#i:e1a64362' : icon);
                    bell.classList[i ? 'add' : 'remove']('is:active');
                });
                setTimeout(count, 10000);
            }
            count();
        }
    } onChange();
    _.on('change', onChange);
})(window, document, _);
