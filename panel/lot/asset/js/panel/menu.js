var dropdowns = document.querySelectorAll('.has\\:menu');

if (dropdowns.length) {
    function remove(but, t) {
        document.querySelectorAll('.lot\\:menu.is\\:enter').forEach(function($$) {
            if ($$ !== but && t !== but.previousElementSibling) {
                $$.classList.remove('is:enter');
                $$.parentNode.classList.remove('is:active');
                $$.previousElementSibling.classList.remove('is:active');
            }
        });
    }
    document.addEventListener("click", function(e) {
        remove(0, e);
    }, false);
    dropdowns.forEach(function($) {
        var menu = $.querySelector('.lot\\:menu');
        if (menu) {
            menu.previousElementSibling.addEventListener("click", function(e) {
                var t = this;
                remove(menu, t);
                setTimeout(function() {
                    t.classList.toggle('is:active');
                    t.parentNode.classList.toggle('is:active');
                    menu.classList.toggle('is:enter');
                }, 1);
                e.preventDefault();
            }, false);
        }
    });
}