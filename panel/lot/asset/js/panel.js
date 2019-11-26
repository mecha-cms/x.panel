(function(win, doc) {

    win.PANEL_ASSET_CSS = {
        'css/panel/field/query': {}
    };

    win.PANEL_ASSET_JS = {
        'js/panel/alert': {},
        'js/panel/field/query': {},
        'js/panel/menu': {},
        'js/panel/tab': {}
    };

    win.PANEL_EVENT_LOAD = new Event('load.panel');

    win.addEventListener('DOMContentLoaded', function() {
        doc.dispatchEvent(win.PANEL_EVENT_LOAD);
    });

    var src = doc.currentScript.src,
        a = src.split('/'), i;

    // `dirname(src, 2)`
    a.pop();
    a.pop();

    src = a.join('/');

    // Load CSS file(s) immediately
    for (i in win.PANEL_ASSET_CSS) {
        if (!win.PANEL_ASSET_CSS[i] || win.PANEL_ASSET_CSS[i].once) {
            continue;
        }
        var link = doc.createElement('link');
        link.href = src + '/' + i + '.css';
        link.rel = 'stylesheet';
        doc.head.appendChild(link);
        win.PANEL_ASSET_CSS[i].once = true;
    }

    // Load JS file(s) on document ready
    doc.addEventListener('load.panel', function() {
        for (i in win.PANEL_ASSET_JS) {
            if (!win.PANEL_ASSET_JS[i] || win.PANEL_ASSET_JS[i].once) {
                continue;
            }
            var script = doc.createElement('script');
            script.src = src + '/' + i + '.js';
            doc.head.appendChild(script);
            win.PANEL_ASSET_JS[i].once = true;
        }
    });

})(window, document);