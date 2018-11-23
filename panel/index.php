<?php

if (!Extend::exist('user')) {
    echo fail('Missing <code>user</code> extension.');
} else if (!glob(USER . DS . '*.page', GLOB_NOSORT)) {
    // TODO: Redirect to user registration form.
}

$state = Extend::state('user');
if ($url->path === ($state['_path'] ?? $state['path'])) {
    $a = Extend::state('panel');
    // Set redirection path after log-in
    Session::reset('url.previous');
    Set::get('kick', $a['path'] . '/::g::/' . $a['$']);
    return;
}

$state = Extend::state('panel');
$p = $state['path'];

$chops = explode('/', $url->path);
$r = array_shift($chops);
$c = str_replace('::', "", array_shift($chops));
$id = array_shift($chops);
$path = implode('/', $chops);

// Trigger notification on comment set
if (Extend::exist('comment')) {
    Hook::set('on.comment.set', function($page) use($language, $p) {
        $path = $this->path;
        Page::set(extend((array) $language->o_message_info_comment_set, [
            'type' => 'Info',
            'link' => $p . '/::g::/' . Path::R($path, LOT, '/')
        ]))->saveTo(LOT . DS . '.message' . DS . md5($path) . '.page');
    });
}

// Trigger notification on poll set
if (Extend::exist('poll')) {
    Hook::set('on.poll.set', function() {});
}

// Trigger notification on markdown link error
if (Plugin::exist('markdown.link')) {
    Hook::set('on.markdown.link.x', function() {
        // TODO
    });
}

if ($r === $p && Is::user()) {
    require __DIR__ . DS . '_index.php';
}