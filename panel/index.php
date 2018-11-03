<?php

if (!Extend::exist('user')) {
    // TODO: Show message that we are missing the user extension.
} else if (!glob(USER . DS . '*.page', GLOB_NOSORT)) {
    // TODO: Redirect to user registration form.
}

$state = Extend::state('user');
if ($url->path === ($state['_path'] ?? $state['path'])) {
    $a = Extend::state('panel');
    // Set redirection path after log in
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

if ($r === $p && $user = Is::user()) {
    require __DIR__ . DS . '_index.php';
}