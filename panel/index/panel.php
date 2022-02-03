<?php namespace x\panel;

require __DIR__ . \D . '..' . \D . 'engine' . \D . 'f.php';
require __DIR__ . \D . '..' . \D . 'engine' . \D . 'fire.php';

$GLOBALS['_'] = $_ = require __DIR__ . \D . '..' . \D . 'engine' . \D . 'r.php';

function route($data) {
    $_ = $data['_'];
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
            $icon .= '<symbol id="i:' . $k . '" viewBox="0 0 24 24">';
            $icon .= 0 === \strpos($v, '<') ? $v : '<path d="' . $v . '"></path>';
            $icon .= '</symbol>';
        }
        $icon .= '</svg>';
    }
    $js = [];
    if (isset($_['file'])) {
        $js['file'] = \To::URL($_['file']);
    }
    if (isset($_['folder'])) {
        $js['folder'] = \To::URL($_['folder']);
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
        'part',
        'path',
        'query',
        'status',
        'task',
        'title',
        'token',
        'type'
    ] as $v) {
        if (isset($_[$v])) {
            $js[$v] = $_[$v];
        }
    }
    $GLOBALS['_']['asset']['script'][] = [
        'content' => 'window._=Object.assign(window._||{},' . \json_encode($js) . ');',
        'id' => false,
        'stack' => 0
    ];
    // Put icon(s) before content
    $GLOBALS['content'] = $icon . $content;
    $GLOBALS['description'] = $_['description'] ?? null;
    $GLOBALS['title'] = (string) $GLOBALS['t']->reverse();
    \x\panel\_asset_set();
    \x\panel\_state_set();
    $data['content'] = \Hook::fire('layout', ['panel']);
    $data['status'] = is_int($_['status']) ? $_['status'] : 404;
    return $data;
}

// Remove front-end route(s)!
\Hook::let('route');

// Load `route.panel` hook only if user is active!
\Hook::set('route', function($data, $path, $query, $hash) use($_) {
    if (\Is::user()) {
        // Load pre-defined route(s) and type(s)
        (static function($_) {
            \extract($GLOBALS, \EXTR_SKIP);
            require __DIR__ . \D . 'panel' . \D . 'route.php';
            require __DIR__ . \D . 'panel' . \D . 'task.php';
            require __DIR__ . \D . 'panel' . \D . 'type.php';
            if (isset($_)) {
                // Update panel data from the route file!
                $GLOBALS['_'] = \array_replace_recursive($GLOBALS['_'], $_);
            }
        })($_);
        \x\panel\_asset_get();
        \x\panel\_asset_let();
        $_ = $GLOBALS['_'] = \Hook::fire('_', [$GLOBALS['_']]) ?? $_;
        $_ = $GLOBALS['_'] = \Hook::fire(\strtr('do.' . ($_['type'] ?? \P) . '.' . ($_['task'] ?? 'get'), "\\", '/'), [$_]) ?? $_;
        if (!empty($_['alert'])) {
            // Has alert data from queue
            $has_alert = true;
            // Make alert section visible
            $_['lot']['desk']['lot']['form']['lot']['alert']['skip'] = false;
            foreach ((array) $_['alert'] as $k => $v) {
                foreach ((array) $v as $vv) {
                    $vv = (array) $vv;
                    \call_user_func("\\Alert::" . $k, ...$vv);
                }
            }
        }
        if ($kick = $_['kick']) {
            // Force redirect!
            $data['kick'] = \is_array($kick) ? \x\panel\to\link($kick) : $kick;
            return $data;
        }
        if (isset($_REQUEST['token'])) {
            $data['kick'] = \x\panel\to\link(['query' => ['token' => null]]);
            return $data;
        }
        if (!empty($_SESSION['alert'])) {
            // Has alert data from previous session
            $has_alert = true;
            // Make alert section visible
            $_['lot']['desk']['lot']['form']['lot']['alert']['skip'] = false;
        }
        if (!empty($has_alert)) {
            $_['lot']['desk']['lot']['form']['lot']['alert']['content'] = \Layout::alert('panel');
        }
        $data['_'] = $_;
        return \Hook::fire('route.panel', [$data, $path, $query, $hash]);
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