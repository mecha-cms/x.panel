(function(win, doc, _) {

    _.ASSET_CSS = {
        'css/panel/field/query': {}
    };

    _.ASSET_JS = {
        'js/panel/alert': {},
        'js/panel/field/query': {},
        'js/panel/field/source': {},
        'js/panel/menu': {},
        'js/panel/tab': {}
    };

    _.READY = new Event('load.panel');

    win.addEventListener('DOMContentLoaded', function() {
        doc.dispatchEvent(_.READY);
    });

    var src = doc.currentScript.src,
        a = src.split('/'), i,
        // `../`
        end = a.pop().split('?')[1] || '0';

    // `../`
    a.pop();

    src = a.join('/');

    // Load CSS file(s) immediately
    for (i in _.ASSET_CSS) {
        if (!_.ASSET_CSS[i] || _.ASSET_CSS[i].once) {
            continue;
        }
        var link = doc.createElement('link');
        link.href = src + '/' + i + '.css?' + end;
        link.rel = 'stylesheet';
        doc.head.appendChild(link);
        _.ASSET_CSS[i].once = true;
    }

    // Load JS file(s) on document ready
    doc.addEventListener('load.panel', function() {
        for (i in _.ASSET_JS) {
            if (!_.ASSET_JS[i] || _.ASSET_JS[i].once) {
                continue;
            }
            var script = doc.createElement('script');
            script.src = src + '/' + i + '.js?' + end;
            doc.head.appendChild(script);
            _.ASSET_JS[i].once = true;
        }
    });

})(window, document, window._ = window._ || {});