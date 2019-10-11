<?php

(function() {
    extract($GLOBALS);
    $p = $_['user']['guard']['path'] ?? $_['user']['path'];
    if ($url->path === $p && empty($_GET['kick'])) {
        $_GET['kick'] = $url . $_['/'] . '::g::' . $_['state']['path'] . '/1';
    }
})();

Hook::set('on.comment.set', function($comment) {
    extract($GLOBALS, EXTR_SKIP);
    $id = uniqid();
    file_put_contents(LOT . DS . '.alert' . DS . $id . '.page', To::page([
        'title' => 'New Comment',
        'description' => 'A new comment has been added.',
        'type' => 'Info',
        'link' => $url . $_['/'] . '::g::' . strtr($comment->path, [
            LOT => "",
            DS => '/'
        ])
    ]));
});