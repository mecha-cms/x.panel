(function(win, doc, _) {
    let timer;
    function onChange() {
        let bell = doc.querySelector('.js\\:alert');
        if (bell) {
            let a = bell.querySelector('a'),
                svg = a.querySelector('use'),
                icon = svg.getAttribute('href'),
                url = a.href;
            url = url.replace(/\/::\w+::\/.*$/, '/::f::/affd6ded?token=' + (_.token || ""));
            function count() {
                fetch(url).then(function(response) {
                    return response.text();
                }).then(function(i) {
                    i = i ? +i : 0;
                    a.title = i;
                    svg.setAttribute('href', i ? '#i:e1a64362' : icon);
                    bell.classList[i ? 'add' : 'remove']('is:active');
                });
                timer && clearTimeout(timer);
                timer = setTimeout(count, 10000);
            }
            count();
        }
    }
    _.on('set', onChange);
    _.on('change', onChange);
})(window, document, _);
