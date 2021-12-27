<?php namespace x\panel;

require __DIR__ . \D . '..' . \D . 'engine' . \D . 'f.php';
require __DIR__ . \D . '..' . \D . 'engine' . \D . 'fire.php';

$GLOBALS['_'] = $_ = require __DIR__ . \D . '..' . \D . 'engine' . \D . 'r.php';

function route($_) {
    $id = \strtok($_['path'], '/');
    $GLOBALS['t'][] = \i('Panel');
    $GLOBALS['t'][] = \i($_['title'] ?? ('x' === $id ? 'Extension' : \To::title($id)));
    $content = "";
    // Load the content first to queue the asset and icon data
    if (isset($_['content'])) {
        $content = \x\panel\type\content([
            'content' => (string) $_['content'],
            'tags' => ['p' => false]
        ], 0);
    } else if (isset($_['lot'])) {
        $content = \x\panel\type\lot([
            'lot' => (array) $_['lot'],
            'tags' => ['p' => false]
        ], 0);
    }
    // Build the icon(s)
    $icon = "";
    $_['icon'] = (array) ($GLOBALS['_']['icon'] ?? []); // Update!
    if (!empty($_['icon'])) {
        $icon .= '<svg xmlns="http://www.w3.org/2000/svg" display="none">';
        foreach ($_['icon'] as $k => $v) {
            $icon .= '<symbol id="icon:' . $k . '" viewBox="0 0 24 24">';
            $icon .= 0 === \strpos($v, '<') ? $v : '<path d="' . $v . '"></path>';
            $icon .= '</symbol>';
        }
        $icon .= '</svg>';
    }
    $data = [];
    if (isset($_['file'])) {
        $data['file'] = \To::URL($_['file']);
    }
    if (isset($_['folder'])) {
        $data['folder'] = \To::URL($_['folder']);
    }
    foreach ([
        'are',
        'author',
        'base',
        'can',
        'has',
        'hash',
        'is',
        'not',
        'path',
        'query',
        'status',
        'task',
        'title',
        'token',
        'type'
    ] as $v) {
        if (isset($_[$v])) {
            $data[$v] = $_[$v];
        }
    }
    $GLOBALS['_']['asset']['script'][] = [
        'content' => 'window._=Object.assign(window._||{},' . \json_encode($data) . ');',
        'id' => false,
        'stack' => 0
    ];
    // Put icon(s) before content
    $GLOBALS['content'] = $icon . $content;
    $GLOBALS['description'] = $_['description'] ?? null;
    $GLOBALS['status'] = is_int($_['status']) ? $_['status'] : 404;
    $GLOBALS['title'] = (string) $GLOBALS['t']->reverse();
    \x\panel\_asset_set();
    \x\panel\_state_set();
    \Hook::fire('layout', ['panel']);
}

// Remove front-end route(s)!
\Hook::let('route');

// Load `route.panel` hook only if user is active!
\Hook::set('route', function($path, $query, $hash) use($_) {
    if (\Is::user()) {
        \x\panel\_asset_get();
        \x\panel\_asset_let();
        \Hook::fire('route.panel', [$_, $path, $query, $hash]);
    }
}, 0);

\Hook::set('route.panel', __NAMESPACE__ . "\\route", 100);

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