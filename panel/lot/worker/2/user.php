<?php

// Only user with status `1` that has access to create user(s)
Config::reset(['panel.desk.header']);
Config::set('panel.nav.s.hidden', true);
if ($c === 's' && !$chops) {
    Config::set('panel.error', true);
}

// Only user with status `1` that has access to edit any user(s)
Config::set('panel.+.' . (HTTP::get('view') ?? $panel->view) . '.tool.g.if', function($file): array {
    return ['x' => !Is::user(Path::N($file))];
});

if ($chops && strpos(Path::F($file) . DS, DS . $user->slug . DS) === false) {
    Config::set('panel.error', true);
}