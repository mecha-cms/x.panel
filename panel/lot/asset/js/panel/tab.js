var tabs = document.querySelectorAll('.lot\\:tab'),
    pushState = 'pushState' in window.history,
    setAction = function($) {
        var href = $.href;
        while ($ && $.nodeName.toLowerCase() !== 'form') {
            $ = $.parentNode;
        }
        $ && $.nodeName.toLowerCase() === 'form' && ($.action = href);
    };

if (tabs.length) {
    var links = [];
    tabs.forEach(function($) {
        var panes = [].slice.call($.children),
            buttons = panes.shift().querySelectorAll('a');
        buttons.forEach(function($$, i) {
            $$._index = i;
            $$.addEventListener("click", function(e) {
                if (!this.parentNode.classList.contains('has:link')) {
                    if (!this.classList.contains('not:active')) {
                        buttons.forEach(function($$$) {
                            $$$.parentNode.classList.remove('is:active');
                            panes[$$$._index] && panes[$$$._index].classList.remove('is:active');
                        });
                        this.parentNode.classList.add('is:active');
                        panes[this._index] && panes[this._index].classList.add('is:active');
                        pushState && window.history.pushState({}, "", this.href);
                        setAction(this);
                    }
                    e.preventDefault();
                }
            }, false);
            links.push($$);
        });
    });
    window.addEventListener("popstate", function() {
        var href = this.location.href;
        for (var i = 0, j = links.length; i < j; ++i) {
            if (links[i].href && links[i].href === href) {
                links[i].click();
                break;
            }
        }
    });
}