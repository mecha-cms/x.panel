<?php

if (null !== State::get('x.comment')) {
    // Send notification
    Hook::set('on.comment.set', function($path) {
        extract($GLOBALS, EXTR_SKIP);
        $id = uniqid();
        file_put_contents(LOT . DS . '.alert' . DS . $id . '.page', To::page([
            'title' => $title = i('New %s', 'Comment'),
            'description' => $description = i('A new %s has been added.', 'comment'),
            'type' => 'Info',
            'link' => $link = $url . $_['/'] . '/::g::' . strtr($path, [
                LOT => "",
                DS => '/'
            ])
        ]));
        // Send email about this!
        if ($email = $state->email) {
            $comment = new Comment($path);
            $content  = '<p style="font-size: 120%; font-weight: bold;">' . $comment->author . '</p>';
            $content .= $comment->content;
            $content .= '<p style="font-size: 80%; font-style: italic;">' . $comment->time->{r('-', '_', $state->language)} . '</p>';
            $content .= '<p><a href="' . $link . '" target="_blank">' . i('Manage') . '</a></p>';
            send($email, $email, $title, $content, [
                'reply-to' => $comment->email ?? $email
            ]);
        }
    });
    // Generate recent comment(s)
    Hook::set('on.comment.set', function($path) {
        extract($GLOBALS, EXTR_SKIP);
        if (!is_file($f = ($d = LOT . DS . 'cache') . DS . 'comments.php')) {
            if (!is_dir($d)) {
                mkdir($d, 0775, true);
            }
            file_put_contents($f, '<?' . 'php return [];');
        }
        $recent = (array) require $f;
        foreach ($recent as $k => $v) {
            if (!is_file(LOT . DS . $v)) {
                unset($recent[$k]);
            }
        }
        array_unshift($recent, strtr($path, [LOT . DS => ""]));
        file_put_contents($f, '<?' . 'php return ' . z(array_slice($recent, 0, $state->x->panel->chunk ?? 20)) . ';');
    });
}
