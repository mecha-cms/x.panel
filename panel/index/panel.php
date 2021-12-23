<?php namespace x\panel;

require __DIR__ . \D . '..' . \D . 'engine' . \D . 'f.php';
require __DIR__ . \D . '..' . \D . 'engine' . \D . 'fire.php';

$GLOBALS['_'] = $_ = require __DIR__ . \D . '..' . \D . 'engine' . \D . 'r.php';

function route($_) {
    $id = \strtok($_['path'], '/');
    $GLOBALS['t'][] = \i('Panel');
    $GLOBALS['t'][] = \i($_['title'] ?? ('x' === $id ? 'Extension' : \To::title($id)));
    if (isset($_['content'])) {
        $GLOBALS['content'] = \x\panel\type\content([
            'content' => (string) $_['content'],
            'tags' => ['p' => false]
        ], 0);
    }
    if (isset($_['lot'])) {
        $GLOBALS['content'] = \x\panel\type\lot([
            'lot' => (array) $_['lot'],
            'tags' => ['p' => false]
        ], 0);
    }
    $GLOBALS['description'] = $_['description'] ?? null;
    $GLOBALS['status'] = $_['status'] ?? 403;
    $GLOBALS['title'] = (string) $GLOBALS['t']->reverse();
    \x\panel\_asset_set();
    \Hook::fire('layout', ['panel']);
}

// Remove front-end route(s)!
\Hook::let('route');

// Load `route.panel` hook only if user is active!
\Hook::set('route', function($path, $query, $hash) use($_) {
    \Is::user() && \Hook::fire('route.panel', [$_, $path, $query, $hash]);
}, 0);

\Hook::set('route.panel', __NAMESPACE__ . "\\route", 100);

\x\panel\_asset_get();

foreach (\glob(\LOT . \D . 'x' . \D . '*' . \D . 'index' . \D . 'panel.php') as $file) {
    // Ignore this very file!
    if (__FILE__ === $file) {
        continue;
    }
    // Include the file only if current extension is active. An active extension will have an `index.php` file.
    if (!\is_file(\dirname($file) . '.php')) {
        continue;
    }
    (static function($f) {
        \extract($GLOBALS, \EXTR_SKIP);
        require $f;
        if (isset($_)) {
            // Update panel data from the special panel file!
            $GLOBALS['_'] = \array_replace_recursive($GLOBALS['_'], $_);
        }
    })($file);
}