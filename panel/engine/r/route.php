<?php namespace _\lot\x\panel;

function route() {
    if (!\Is::user()) {
        \Guard::kick("");
    }
    // Load default panel definition
    $GLOBALS['_'] = require __DIR__ . \DS . '..' . \DS . 'r.php';
    extract($GLOBALS, \EXTR_SKIP);
    $route = $type = false;
    $_ = \_\lot\x\panel\_error_route_check();
    foreach (\step($_['path'], '/') as $v) {
        if (\function_exists($fn = __NAMESPACE__ . "\\route\\" . \f2p(\strtr($v, [
            '.' => '__',
            '/' => '.'
        ])))) {
            $route = $fn;
            // Custom route is available, remove the error status!
            $_['is']['error'] = $GLOBALS['_']['is']['error'] = false;
            $_['title'] = $GLOBALS['_']['title'] = null;
            break;
        }
    }
    $f = $_['f'];
    if ('get' === $_['form']['type']) {
        if (!$route && !empty($_['is']['error'])) {
            $_ = \_\lot\x\panel\_set();
            $_ = \_\lot\x\panel\_set_state();
            $this->layout($_['layout'] ?? $_['is']['error'] . '/panel');
        }
    }
    $_ = \_\lot\x\panel\_set();
    $_ = \_\lot\x\panel\_set_state();
    if (!isset($_['type'])) {
        // Auto-detect layout type
        if ($f) {
            if (\is_dir($f)) {
                $_['type'] = 'folder';
            } else if (\is_file($f)) {
                $_['type'] = 'file';
            }
            // Put data
            $GLOBALS['_'] = $_;
        }
        // Manually set layout type based on file path
        foreach (\array_reverse(\step($_['path'], '/')) as $v) {
            (static function($_type) use(&$type) {
                if (\is_file($_type)) {
                    extract($GLOBALS, \EXTR_SKIP);
                    if (isset($_) && \is_array($_)) {
                        $_ = (array) require $_type;
                        $GLOBALS['_'] = \array_replace_recursive($GLOBALS['_'], $_);
                    }
                    $type = $_type;
                }
            })(__DIR__ . \DS . 'lot' . \DS . 'page' . ($_['i'] ? 's' : "") . \DS . $v . '.php');
        }
        // Get data
        $_ = $GLOBALS['_'];
    }
    // Set layout type
    if (!$type) {
        $k = \explode('/', $_['type'] ?? \P, 2);
        $k[0] .= ($_['i'] ? 's' : "");
        $type = __DIR__ . \DS . 'type' . \DS . \implode(\DS, $k) . '.php';
    }
    // Define lot based on the current type
    \is_file($type) && (static function($type) {
        extract($GLOBALS, \EXTR_SKIP);
        if (isset($_) && \is_array($_)) {
            $_ = (array) require $type;
            $GLOBALS['_'] = \array_replace_recursive($GLOBALS['_'], $_);
        }
    })($type);
    // Filter by status
    \is_file($v = __DIR__ . \DS . 'lot' . \DS . 'user' . \DS . $user['status'] . '.php') && (static function($v) {
        extract($GLOBALS, \EXTR_SKIP);
        require $v;
        if (isset($_) && \is_array($_)) {
            $GLOBALS['_'] = \array_replace_recursive($GLOBALS['_'], $_);
        }
    })($v);
    // Get data
    $_ = $GLOBALS['_'];
    // Filter by route function
    if ($route) {
        // Remove `_\lot\x\panel\route` prefix from the captured route function
        $path = \implode('/', \map(\explode("\\", \substr($route, 20)), function($v) {
            // Convert property name to file name
            return \strtr(\p2f($v), ['__' => '.']);
        }));
        if ($r = \fire($route, [$_, $path], $this)) {
            $_ = $r;
        }
    }
    // Filter by hook
    if ($r = \Hook::fire('_', [$_])) {
        $_ = $r;
    }
    // Put data
    $GLOBALS['_'] = $_;
    if (isset($_['form']['lot']['token'])) {
        if (empty($_['form']['lot']['token']) || $_['token'] !== $_['form']['lot']['token']) {
            if ('post' === $_['form']['type']) {
                if ('g' === $_['task'] || 's' === $_['task']) {
                    $_['alert']['error'][] = 'Invalid token.';
                }
            } else {
                if ('f' === $_['task'] || 'l' === $_['task']) {
                    $_['alert']['error'][] = 'Invalid token.';
                    $_['kick'] = $url . $_['/'] . '/::g::/' . \dirname($_['path']) . '/1' . $url->query('&', [
                        'token' => false
                    ]);
                }
            }
        }
        // Put data
        $GLOBALS['_'] = $_;
        // Include form task(s)
        \is_file($v = __DIR__ . \DS . 'task' . \DS . $_['task'] . '.php') && (static function($v) {
            extract($GLOBALS, \EXTR_SKIP);
            require $v;
            if (isset($_) && \is_array($_)) {
                $GLOBALS['_'] = \array_replace_recursive($GLOBALS['_'], $_);
            }
        })($v);
        // Get data
        $_ = $GLOBALS['_'];
        if (isset($_['type'])) {
            $hooks = \map(\step($_['type'], '/'), function($hook) use($_) {
                return 'do.' . $hook . '.' . ([
                    'f' => 'fire',
                    'g' => 'get',
                    'l' => 'let',
                    's' => 'set'
                ][$_['task']] ?? '?');
            });
            foreach (\array_reverse($hooks) as $hook) {
                if ($r = \Hook::fire($hook, [$_])) {
                    $_ = $r;
                }
            }
        } else {
            // Form data has been processed through a blank layout
        }
    } else {
        // Missing `<input name="token">`
        if ('l' === $_['task']) {
            $_['kick'] = \strtr($url->current, ['::l::' => '::g::']);
        }
    }
    // Has alert data from queue
    if (!empty($_['alert'])) {
        // Make alert section visible
        $_['lot']['desk']['lot']['form']['lot']['alert']['skip'] = false;
        foreach ((array) $_['alert'] as $k => $v) {
            foreach ((array) $v as $vv) {
                $vv = (array) $vv;
                \call_user_func("\\Alert::" . $k, ...$vv);
            }
        }
    }
    if (isset($_['kick'])) {
        \Guard::kick($_['kick']);
    } else {
        if (isset($_['form']['lot']['token'])) {
            \Guard::kick($url->clean . $url->i . $url->query('&', [
                'token' => false
            ]) . $url->hash);
        }
    }
    \State::set('[layout].type:' . ($_['type'] ?? 'blank'), true);
    $n = \ltrim($_['chops'][0] ?? "", '_.-');
    // Put data
    $GLOBALS['_'] = $_;
    $GLOBALS['t'][] = \i('Panel');
    if (isset($_['title'])) {
        $GLOBALS['t'][] = \i($_['title']); // Custom panel title
    } else {
        $GLOBALS['t'][] = isset($_['path']) ? \i('x' === $n ? 'Extension' : \To::title($n)) : null;
    }
    // Has alert data from previous session
    if (\count($GLOBALS['alert'] ?? [])) {
        // Make alert section visible
        $_['lot']['desk']['lot']['form']['lot']['alert']['skip'] = false;
    }
    $this->layout($_['layout'] ?? '200/panel');
}

// Back-end route must be set in the highest priority!
\Route::set($_['/'] . '/*', 200, __NAMESPACE__ . "\\route", -1);
