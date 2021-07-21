<?php

Hook::set('on.user.exit', function() {
    unset($_SESSION['_']); // Clear all file and folder marker(s)
});

$email = State::get('email');

if (null !== State::get('x.comment')) {
    // Send notification
    if ($email && Is::email($email)) {
        Hook::set('on.comment.set', function($path) use($email) {
            extract($GLOBALS, EXTR_SKIP);
            $link = $_['/'] . '/::g::' . strtr($path, [
                LOT => "",
                DS => '/'
            ]);
            $comment = new Comment($path);
            $title = i('New Comment');
            $content  = '<p style="font-size: 120%; font-weight: bold;">' . $comment->author . '</p>';
            $content .= $comment->content;
            $content .= '<p style="font-size: 80%; font-style: italic;">' . $comment->time->{r('-', '_', $state->language)} . '</p>';
            $content .= '<p><a href="' . $link . '" target="_blank">' . i('Manage') . '</a></p>';
            send($email, $email, $title, $content, [
                'reply-to' => $comment->email ?? $email
            ]);
        });
    }
    // Generate recent comment cache
    Hook::set('on.comment.set', function($path) {
        extract($GLOBALS, EXTR_SKIP);
        // `dechex(crc32('comments.info'))`
        if (!is_file($f = ($d = LOT . DS . 'cache') . DS . '8bead58f.php')) {
            if (!is_dir($d)) {
                mkdir($d, 0775, true);
            }
            file_put_contents($f, '<?' . 'php return [0];');
        }
        $info = (array) require $f;
        $info[0] = $info[0] + 1;
        file_put_contents($f, '<?' . 'php return ' . z($info) . ';');
        // `dechex(crc32('comments'))`
        if (!is_file($f = ($d = LOT . DS . 'cache') . DS . '5f9e962a.php')) {
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
        file_put_contents($f, '<?' . 'php return ' . z(array_slice($recent, 0, $_['chunk'])) . ';');
    });
    // Generate recent comment cache for the first time
    if (!is_file($f = ($d = LOT . DS . 'cache') . DS . '5f9e962a.php')) {
        if (!is_dir($d)) {
            mkdir($d, 0775, true);
        }
        $recent = [];
        foreach (g(LOT . DS . 'comment', 'archive,draft,page', true) as $k => $v) {
            $recent[basename($k)] = strtr($k, [LOT . DS => ""]);
        }
        krsort($recent);
        file_put_contents($f, '<?' . 'php return ' . z(array_values(array_slice($recent, 0, $_['chunk']))) . ';');
    }
}
