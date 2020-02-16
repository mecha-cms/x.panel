(function(win, doc, _) {
    function onChange() {
        var dropdowns = doc.querySelectorAll('.has\\:menu');
        function doHide(but, t) {
            doc.querySelectorAll('.lot\\:menu.is\\:enter').forEach(function($$) {
                if ($$ !== but && t !== but.previousElementSibling) {
                    $$.classList.remove('is:enter');
                    $$.parentNode.classList.remove('is:active');
                    $$.previousElementSibling.classList.remove('is:active');
                }
            });
        }
        function onClickHide(e) {
            doHide(0, e);
        }
        function onClickShow(e) {
            var t = this,
                menu = t.nextElementSibling;
            doHide(menu, e);
            setTimeout(function() {
                t.classList.toggle('is:active');
                t.parentNode.classList.toggle('is:active');
                menu.classList.toggle('is:enter');
            }, 1);
            e.preventDefault();
        }
        if (dropdowns.length) {
            doc.addEventListener("click", onClickHide, false);
            dropdowns.forEach(function($) {
                var menu = $.querySelector('.lot\\:menu');
                if (menu) {
                    menu.previousElementSibling.addEventListener('click', onClickShow, false);
                }
            });
        }
    } onChange();
    _.on('change', onChange);
})(window, document, _);
