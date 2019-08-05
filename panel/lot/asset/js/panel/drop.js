var dropdowns = document.querySelectorAll('.drop');

if (dropdowns.length) {
    function remove(but, t) {
        document.querySelectorAll('.lot\\:menu.enter').forEach(function($$) {
            if ($$ !== but && t !== but.previousElementSibling) {
                $$.classList.remove('enter');
                $$.parentNode.classList.remove('active');
                $$.previousElementSibling.classList.remove('active');
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
                    t.classList.toggle('active');
                    t.parentNode.classList.toggle('active');
                    menu.classList.toggle('enter');
                }, 1);
                e.preventDefault();
            }, false);
        }
    });
}