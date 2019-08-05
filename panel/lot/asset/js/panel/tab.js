var tabs = document.querySelectorAll('.lot\\:tab'),
    pushState = 'pushState' in window.history;

if (tabs.length) {
    var links = [];
    tabs.forEach(function($) {
        var panes = [].slice.call($.children),
            buttons = panes.shift().querySelectorAll('a');
        buttons.forEach(function($$, i) {
            $$._index = i;
            $$.addEventListener("click", function(e) {
                if (!this.classList.contains('disabled')) {
                    buttons.forEach(function($$$) {
                        $$$.parentNode.classList.remove('active');
                        panes[$$$._index] && panes[$$$._index].classList.remove('active');
                    });
                    this.parentNode.classList.add('active');
                    panes[this._index] && panes[this._index].classList.add('active');
                    pushState && window.history.pushState({}, "", this.href);
                }
                e.preventDefault();
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