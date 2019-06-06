var dropdowns = document.querySelectorAll('.dropdown');

if (dropdowns.length) {
    function remove(but, t) {
        document.querySelectorAll('.menu-group.enter').forEach(function($$) {
            if ($$ !== but && t !== but.previousElementSibling) {
                $$.classList.remove('enter');
                $$.previousElementSibling.classList.remove('active');
            }
        });
    }
    document.addEventListener("click", function(e) {
        remove(0, e);
    }, false);
    dropdowns.forEach(function($) {
        var menu = $.querySelector('.menu-group');
        if (menu) {
            menu.previousElementSibling.addEventListener("click", function(e) {
                var t = this;
                remove(menu, t);
                setTimeout(function() {
                    t.classList.toggle('active');
                    menu.classList.toggle('enter');
                }, 1);
                e.preventDefault();
            }, false);
        }
    });
}