<?php

(function() {
    extract($GLOBALS);
    $p = $_['user']['guard']['path'] ?? $_['user']['path'];
    if ($url->path === $p && empty($_GET['kick'])) {
        $_GET['kick'] = $kick = $url . $_['/'] . '::g::' . $_['state']['path'] . '/1';
        Hook::set('content', function($content) use($kick) {
            return Is::user() ? str_replace('</p>', ' <a class="button" href="' . $kick . '">' . i('Panel') . '</a></p>', $content) : $content;
        });
    }
})();
