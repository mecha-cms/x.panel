<?php namespace x\panel;

require __DIR__ . \D . '..' . \D . 'engine' . \D . 'f.php';
require __DIR__ . \D . '..' . \D . 'engine' . \D . 'fire.php';

$GLOBALS['_'] = $_ = require __DIR__ . \D . '..' . \D . 'engine' . \D . 'r.php';

// \test(type\files\trash());exit;

function route($content, $path, $query, $hash) {
    if (null !== $content || !\Is::user()) {
        return $content;
    }
    $_ = $GLOBALS['_'] ?? [];
    // Check for update(s)
    if ('GET' === $_SERVER['REQUEST_METHOD'] && 'get' === $_['task'] && empty($_['query']['token'])) {
        \x\panel\_git_sync();
    }
    // Load pre-defined route(s) and type(s)
    (static function () {
        \extract($GLOBALS);
        require __DIR__ . \D . 'panel' . \D . 'route.php';
        require __DIR__ . \D . 'panel' . \D . 'status.php';
        require __DIR__ . \D . 'panel' . \D . 'task.php';
        require __DIR__ . \D . 'panel' . \D . 'type.php';
        if (isset($_)) {
            // Update panel data from the route file!
            $GLOBALS['_'] = \array_replace_recursive($GLOBALS['_'], $_);
        }
    })();
    \x\panel\_asset_get();
    \x\panel\_asset_let();
    $_ = $GLOBALS['_'] = \Hook::fire('_', [$GLOBALS['_']]) ?? $_;
    $task = \strtr($_['task'] ?? 'get', "\\", '/');
    $types = \step(\strtr($_['type'] ?? \P, "\\", '/'), '/');
    foreach (\array_reverse($types) as $type) {
        $_ = $GLOBALS['_'] = \Hook::fire('do.' . $type . '.' . $task, [$_]) ?? $_;
    }
    if (!empty($_['alert']) && \class_exists("\\Alert")) {
        // Has alert data from queue
        $has_alert = true;
        // Make alert section visible
        if (!empty($_['lot']['desk']['lot']['form']['lot']['alert'])) {
            $_['lot']['desk']['lot']['form']['lot']['alert']['skip'] = false;
        }
        foreach ((array) $_['alert'] as $k => $v) {
            foreach ((array) $v as $vv) {
                $vv = (array) $vv;
                \call_user_func("\\Alert::" . $k, ...$vv);
            }
        }
    }
    if ($kick = $_['kick']) {
        // Force redirect!
        \kick(\is_array($kick) ? \x\panel\to\link($kick) : $kick);
    }
    if (isset($_REQUEST['token'])) {
        \kick(\x\panel\to\link(['query' => ['token' => null]]));
    }
    if (!empty($_SESSION['alert'])) {
        // Has alert data from previous session
        $has_alert = true;
        // Make alert section visible
        if (!empty($_['lot']['desk']['lot']['form']['lot']['alert'])) {
            $_['lot']['desk']['lot']['form']['lot']['alert']['skip'] = false;
        }
    }
    if (!empty($has_alert) && !empty($_['lot']['desk']['lot']['form']['lot']['alert']) && \class_exists("\\Layout")) {
        $_['lot']['desk']['lot']['form']['lot']['alert']['content'] = \Layout::alert('panel');
    }
    // Update panel data
    $GLOBALS['_'] = $_;
    return \Hook::fire('route.panel', [$content, $path, $query, $hash]);
}

function route__panel($content, $path, $query, $hash) {
    if (null !== $content) {
        return $content;
    }
    \extract($GLOBALS, \EXTR_SKIP);
    if ($_['status'] >= 400) {
        $_['lot']['bar']['skip'] = true;
        $_['lot']['desk']['lot']['form']['skip'] = true;
        $_['lot']['desk']['lot']['alert'] = \array_replace_recursive([
            '2' => ['role' => 'status'],
            'content' => \i('%s does not exist.', ['Page']),
            'icon' => 'M12,2A9,9 0 0,0 3,11V22L6,19L9,22L12,19L15,22L18,19L21,22V11A9,9 0 0,0 12,2M9,8A2,2 0 0,1 11,10A2,2 0 0,1 9,12A2,2 0 0,1 7,10A2,2 0 0,1 9,8M15,8A2,2 0 0,1 17,10A2,2 0 0,1 15,12A2,2 0 0,1 13,10A2,2 0 0,1 15,8Z',
            'level' => 0,
            'stack' => 10,
            'type' => 'title'
        ], $_['lot']['desk']['lot']['alert'] ?? []);
        $_['title'] = $_['title'] ?? 'Error';
    }
    $content = $icon = $list = "";
    $id = \strtok($_['path'], '/');
    // Load the content first to queue the asset, icon, and (data)list
    if (isset($_['content'])) {
        $content = \x\panel\lot\type\content([
            'content' => (string) $_['content'],
            'tags' => ['p' => false]
        ], 0);
    } else if (isset($_['lot'])) {
        $content = \x\panel\lot\type\lot([
            'lot' => (array) $_['lot'],
            'tags' => ['p' => false]
        ], 0);
    }
    // Update!
    $_['data-list'] = (array) ($GLOBALS['_']['data-list'] ?? []);
    $_['icon'] = (array) ($GLOBALS['_']['icon'] ?? []);
    if (!empty($_['data-list'])) {
        foreach ($_['data-list'] as $k => $v) {
            $list .= '<datalist id="l:' . $k . '">';
            foreach ($v as $kk => $vv) {
                if (false === $vv || null === $vv || !empty($vv['skip'])) {
                    continue;
                }
                $list .= new \HTML(['option', \s(\is_array($vv) ? ($vv['value'] ?? $vv['title'] ?? $kk) : $vv), [
                    'disabled' => \is_array($vv) && \array_key_exists('active', $vv) && !$vv['active']
                ]]);
            }
            $list .= '</datalist>';
        }
    }
    if (!empty($_['icon'])) {
        $icon .= '<svg display="none" xmlns="http://www.w3.org/2000/svg">';
        foreach ($_['icon'] as $k => $v) {
            $icon .= '<symbol id="i:' . $k . '" viewBox="0 0 24 24">';
            if (false === $v || null === $v) {
                continue;
            }
            // Raw XML input
            if (0 === \strpos($v, '<')) {
                $icon .= $v;
            // Path direction input
            } else {
                $icon .= '<path d="' . $v . '"></path>';
            }
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
        'as',
        'author',
        'base',
        'can',
        'chunk',
        'count',
        'deep',
        'has',
        'hash',
        'is',
        'not',
        'of',
        'part',
        'path',
        'query',
        'sort',
        'sort',
        'status',
        'task',
        'title',
        'token',
        'type',
        'with',
        'x'
    ] as $v) {
        if (isset($_[$v])) {
            $js[$v] = $_[$v];
        }
    }
    $GLOBALS['_']['asset']['script'] = [
        'id' => false,
        'link' => 'data:text/js;base64,' . \base64_encode('window._=Object.assign(window._||{},' . \json_encode($js) . ');'),
        'stack' => 0
    ];
    $GLOBALS['content'] = $icon . $content . $list;
    $GLOBALS['description'] = \i(...((array) ($_['description'] ?? "")));
    $GLOBALS['t'][] = \i('Panel');
    $GLOBALS['t'][] = \i(...((array) ($_['title'] ?? ('x' === $id ? 'Extension' : ('y' === $id ? 'Layout' : \To::title($id))))));
    $GLOBALS['title'] = (string) $GLOBALS['t']->reverse();
    \x\panel\_asset_set();
    \x\panel\_state_set();
    $_ = $GLOBALS['_']; // Update!
    return ['panel', [], (int) ($_['status'] ?? 404)];
}

// Remove all front-end route(s)
\Hook::let('route');

// But final front-end route(s)
\Hook::set('route', "x\\layout\\route", 1000);

// Load `route.panel` hook only if user is active!
\Hook::set('route', __NAMESPACE__ . "\\route", 0);
\Hook::set('route.panel', __NAMESPACE__ . "\\route__panel", 100);

foreach (\glob(\LOT . \D . '{x,y}' . \D . '*' . \D . 'index' . \D . 'panel.php', \GLOB_BRACE | \GLOB_NOSORT) as $file) {
    // Ignore this very file!
    if (__FILE__ === $file) {
        continue;
    }
    // Include this file only if current extension/layout is active.
    // An active extension/layout will have an `index.php` file.
    if (!\is_file(\dirname($file) . '.php')) {
        continue;
    }
    (static function ($f) {
        \extract($GLOBALS, \EXTR_SKIP);
        require $f;
        if (isset($_)) {
            // Update panel data from the special panel file!
            $GLOBALS['_'] = \array_replace_recursive($GLOBALS['_'], $_);
        }
    })($file);
}