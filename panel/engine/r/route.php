<?php namespace _\lot\x\panel;

function route() {
    if (!\Is::user()) {
        \Guard::kick("");
    }
    // Load default panel definition
    $GLOBALS['_'] = require __DIR__ . \DS . '..' . \DS . 'r.php';
    extract($GLOBALS, \EXTR_SKIP);
    $route = false;
    if ($r = \_\lot\x\panel\_error_route_check($_)) {
        $_ = $GLOBALS['_'] = $r;
    }
    foreach (\step($_['path'], '/') as $v) {
        if (\function_exists($fn = __NAMESPACE__ . "\\route\\" . \f2p(\strtr($v, '/', '.')))) {
            $route = $fn;
            // Custom route is available, remove the error status!
            $_['is']['error'] = $GLOBALS['_']['is']['error'] = false;
            $_['title'] = $GLOBALS['_']['title'] = null;
            break;
        }
    }
    $set = static function() {
        // Load panel definition from a file stored in `.\lot\x\*\index\panel.php`
        foreach ($GLOBALS['X'][1] as $v) {
            \is_file($v = \Path::F($v) . \DS . 'panel.php') && (static function($v) {
                extract($GLOBALS, \EXTR_SKIP);
                require $v;
                if (isset($_) && \is_array($_)) {
                    $GLOBALS['_'] = \array_replace_recursive($GLOBALS['_'], $_);
                }
            })($v);
        }
        // Load panel definition from a file stored in `.\lot\layout\index\panel.php`
        \is_file($v = \LOT . \DS . 'layout' . \DS . 'index' . \DS . 'panel.php') && (static function($v) {
            extract($GLOBALS, \EXTR_SKIP);
            require $v;
            if (isset($_) && \is_array($_)) {
                $GLOBALS['_'] = \array_replace_recursive($GLOBALS['_'], $_);
            }
        })($v);
    };
    $f = $_['f'];
    // Pre-define state
    foreach (['are', 'can', 'has', 'is', 'not', '[layout]'] as $v) {
        if (isset($_[$v]) && \is_array($_[$v])) {
            \State::set($v, $_[$v]);
        }
    }
    if ('get' === $_['form']['type']) {
        if (!$route && !empty($_['is']['error'])) {
            $this->layout($_['layout'] ?? $_['is']['error'] . '/panel');
        }
    }
    $data = null;
    if (!isset($_GET['type']) && !isset($_['type'])) {
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
            (static function($v) use(&$data) {
                if (\is_file($v)) {
                    extract($GLOBALS, \EXTR_SKIP);
                    require ($data = $v);
                    if (isset($_) && \is_array($_)) {
                        $GLOBALS['_'] = \array_replace_recursive($GLOBALS['_'], $_);
                    }
                }
            })(__DIR__ . \DS . 'lot' . \DS . 'page' . ($_['i'] ? 's' : "") . \DS . $v . '.php');
        }
        // Get data
        $_ = $GLOBALS['_'];
    }
    // Set layout type
    if (!$data) {
        $k = \explode('/', $_['type'] ?? \P, 2);
        $k[0] .= ($_['i'] ? 's' : "");
        $data = __DIR__ . \DS . 'type' . \DS . \implode(\DS, $k) . '.php';
    }
    // Load panel definition from other extension(s)
    $set();
    // Define lot with no filter
    (static function($data) {
        extract($GLOBALS, \EXTR_SKIP);
        $_['lot'] = \array_replace_recursive($_['lot'] ?? [], (array) (\is_file($data) ? require $data : []));
        $GLOBALS['_'] = \array_replace_recursive($GLOBALS['_'], $_);
    })($data);
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
    if ($route && $r = \fire($route, [$_], $this)) {
        $_ = $r;
    }
    // Filter by hook
    if ($r = \Hook::fire('_', [$_])) {
        $_ = $r;
    }
    // Put data
    $GLOBALS['_'] = $_;
    if (isset($_['form']['lot']['token'])) {
        if (empty($_['form']['lot']['token']) || $_['form']['lot']['token'] !== $_['token']) {
            if ('post' === $_['form']['type']) {
                if ('g' === $_['task'] || 's' === $_['task']) {
                    $_['alert']['error'][] = 'Invalid token.';
                }
            } else {
                if ('f' === $_['task'] || 'l' === $_['task']) {
                    $_['alert']['error'][] = 'Invalid token.';
                    $_['kick'] = $url . $_['/'] . '/::g::/' . \dirname($_['path']) . '/1' . $url->query('&', ['token' => false]);
                }
            }
        }
        // Put data
        $GLOBALS['_'] = $_;
        // Include form task(s)
        if (\is_file($v = __DIR__ . \DS . 'task' . \DS . $_['task'] . '.php')) {
            (static function($v) {
                extract($GLOBALS, \EXTR_SKIP);
                require $v;
                if (isset($_) && \is_array($_)) {
                    $GLOBALS['_'] = \array_replace_recursive($GLOBALS['_'], $_);
                }
            })($v);
        }
        // Get data
        $_ = $GLOBALS['_'];
        if (isset($_['type'])) {
            $hooks = \map(\step($_['type'], '/'), function($hook) use($_) {
                return 'do.' . $hook . '.' . ([
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
    // Put data
    $GLOBALS['_'] = $_;
    // Load asset(s)
    if (!empty($_['asset'])) {
        foreach ((array) $_['asset'] as $k => $v) {
            if (null === $v || false === $v || !empty($v['skip'])) {
                continue;
            }
            if ('script' === $k || 'style' === $k || 'template' === $k) {
                foreach ((array) $_['asset'][$k] as $kk => $vv) {
                    if (null === $vv || false === $vv || !empty($vv['skip'])) {
                        continue;
                    }
                    if (!\is_numeric($kk)) {
                        $vv[2]['id'] = $kk;
                    } else if (!empty($vv['id'])) {
                        $vv[2]['id'] = $vv['id'];
                    }
                    $content = (string) ($vv[1] ?? $vv['content'] ?? "");
                    $stack = (float) ($vv['stack'] ?? 10);
                    \call_user_func("\\Asset::" . $k, $content, $stack, (array) ($vv[2] ?? []));
                }
                continue;
            }
            $path = (string) ($v['path'] ?? $v['link'] ?? $v['url'] ?? $k);
            $stack = (float) ($v['stack'] ?? 10);
            if (!\is_numeric($k) && (
                !empty($v['link']) ||
                !empty($v['path']) ||
                !empty($v['url'])
            )) {
                $v[2]['id'] = $k;
            } else if (!empty($v['id'])) {
                $v[2]['id'] = $v['id'];
            }
            \Asset::set($path, $stack, (array) ($v[2] ?? []));
        }
    }
    $this->layout($_['layout'] ?? '200/panel');
}

// Back-end route must be set in the highest priority!
\Route::set($_['/'] . '/*', 200, __NAMESPACE__ . "\\route", -1);
