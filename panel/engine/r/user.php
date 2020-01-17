<?php

(function() {
    extract($GLOBALS);
    $p = $_['user']['guard']['path'] ?? $_['user']['path'];
    if ($url->path === $p && empty($_GET['kick'])) {
        $_GET['kick'] = $kick = $url . $_['/'] . '::g::' . $_['state']['path'] . '/1';
        /*
        Hook::set('content', function($content) use($kick) {
            return Is::user() ? str_replace('</p>', ' <a class="button" href="' . $kick . '">' . i('Panel') . '</a></p>', $content) : $content;
        });
        */
    }
})();

if (null !== State::get('x.comment')) {
    // Send notification
    Hook::set('on.comment.set', function($path) {
        extract($GLOBALS, EXTR_SKIP);
        $id = uniqid();
        file_put_contents(LOT . DS . '.alert' . DS . $id . '.page', To::page([
            'title' => $title = i('New %s', 'Comment'),
            'description' => $description = i('A new %s has been added.', 'comment'),
            'type' => 'Info',
            'link' => $url . $_['/'] . '::g::' . strtr($path, [
                LOT => "",
                DS => '/'
            ])
        ]));
        // TODO: Send email about this!
        if ($email = $state->email) {
            send($email, $title, '<p>' . $description . '</p><p><a href="' . $url . $_['/'] . '::g::/.alert/1' . '">' . i('Manage') . '</a></p>');
        }
    });
}
