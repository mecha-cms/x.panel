<?php

if (!is_dir(LOT . D . 'user') || !isset($state->x->user)) {
    return;
}

// Clear the rest of file and folder marker(s)
Hook::set('on.user.exit', function() {
    unset($_SESSION['_']);
});

/*

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

*/

$path = trim($url->path ?? "", '/');
$route = trim($state->x->panel->guard->route ?? $state->x->user->guard->route ?? $state->x->user->route ?? 'user', '/');

$req = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$test = preg_match('/^' . x($route) . '\/(fire\/[^\/]+|[gls]et)\/(.+)$/', $path, $m);

// Create `$user` variable just in case `user` extension is too late to be loaded due to the default extension order.
// Since `panel` is less than `user` when sorted alphabetically, then this `panel` extension will most likely be loaded
// before `user` extension. Here we use the user’s cookie data to reconstruct the variable.
if (empty($user) && ($key = cookie('user.key')) && ($token = cookie('user.token'))) {
    if (is_file($file = LOT . D . 'user' . D . $key . '.page')) {
        if ($token === content(LOT . D . 'user' . D . $key . D . 'token.data')) {
            $GLOBALS['user'] = $user = new User($file);
        }
    }
}

// Someone just tried to replace you!
if (!empty($user) && !($user instanceof User)) {
    abort('<code>$user</code> must be an instance of <code>User</code>.');
}

// File/folder path takes from the current path or from the current path without the
// numeric suffix which is commonly used to indicate current pagination offset.
$f = $part = 0;
if ($test) {
    if (!$f = stream_resolve_include_path(LOT . D . $m[2])) {
        if (preg_match('/^(.*)\/([1-9]\d*)$/', $m[2], $mm)) {
            $f = stream_resolve_include_path(LOT . D . $mm[1]);
            $part = (int) $mm[2];
            $m[2] = $mm[1]; // Path without the numeric suffix
        }
    }
}

$query = From::query($_SERVER['QUERY_STRING']);
$GLOBALS['_'] = $_ = array_replace_recursive([
    '0' => null,
    '1' => null,
    '2' => [],
    'alert' => [],
    'are' => [],
    'as' => [],
    'asset' => [],
    'author' => $user->user ?? null,
    'base' => $url . '/' . $route,
    'can' => [],
    'chunk' => null, // Default is `20`
    'content' => null,
    'count' => 0,
    'deep' => null, // Default is `0`
    'description' => null,
    'file' => $f && is_file($f) ? $f : null,
    'folder' => $f && is_dir($f) ? $f : null,
    'has' => [],
    'hash' => $url['hash'],
    'icon' => [],
    'is' => [],
    'kick' => null,
    'lot' => [],
    'not' => [],
    'of' => [],
    'part' => (int) $part,
    'path' => $test ? $m[2] : null,
    'query' => $query,
    'sort' => null, // Default is `[1, 'path']`
    'status' => $f ? 200 : 404,
    'task' => $GLOBALS['_' . $req]['task'] ?? ($test ? $m[1] : null),
    'title' => null,
    'token' => $user->token ?? null,
    'type' => $GLOBALS['_' . $req]['type'] ?? null,
    'with' => [],
    'x' => null
], $GLOBALS['_'] ?? []);

// Modify default log-in redirection to the panel page if it is not set
if ('GET' === $req && !array_key_exists('kick', $_GET)) {
    if ($path === trim($state->x->user->guard->route ?? $state->x->user->route ?? 'user', '/')) {
        $_GET['kick'] = '/' . $route . '/get/' . trim($state->x->panel->route ?? 'asset', '/');
    }
}

// Load the panel interface only if the location value is at least started with `http://127.0.0.1/panel/`
if (!empty($user) && 0 === strpos($path . '/', $route . '/') && $test) {
    require __DIR__ . D . 'index' . D . 'panel.php';
}