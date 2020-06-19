<?php namespace _\lot\x\panel;

// Load task(s) before everything else!
if (\is_file($v = __DIR__ . \DS . 'task' . \DS . $_['task'] . '.php')) {
    (function($v) {
        extract($GLOBALS, \EXTR_SKIP);
        require $v;
    })($v);
}

function route() {
    if (!\Is::user()) {
        \Guard::kick("");
    }
    extract($GLOBALS, \EXTR_SKIP);
    $f = $_['f'];
    $route = false;
    foreach (\step($_['path'], '/') as $v) {
        if (\function_exists($fn = __NAMESPACE__ . "\\route\\" . \f2p(\strtr($v, '/', '.')))) {
            $route = $fn;
            break;
        }
    }
    if ('GET' === $_SERVER['REQUEST_METHOD']) {
        // Redirect if file already exists
        if ('s' === $_['task'] && $f && \is_file($f)) {
            $_['alert']['info'][] = ['File %s already exists.', ['<code>' . \_\lot\x\panel\h\path($f) . '</code>']];
            $_['kick'] = \str_replace('::s::', '::g::', $url->current);
        }
        if (
            // No route match
            !$route && (
                // Trying to get file that does not exist
                'g' === $_['task'] && !$f ||
                // Trying to set file from a folder that does not exist
                's' === $_['task'] && (!$f || !\is_dir($f))
            )
        ) {
            $GLOBALS['t'][] = \i('Error');
            \State::set([
                '[layout]' => ['layout:' . $_['layout'] => false],
                'is' => [
                    'error' => 404
                ]
            ]);
            $this->status(404);
            $this->view('panel.404');
        }
    }
    // Pre-define state
    \State::set([
        'has' => [
            'parent' => \count($_['chops']) > 1,
        ],
        'is' => [
            'error' => false,
            'page' => !isset($_['i']),
            'pages' => isset($_['i'])
        ]
    ]);
    $state_d = __DIR__ . \DS . 'state';
    if (\defined("\\DEBUG") && \DEBUG && isset($_GET['test'])) {
        $state_f = $state_d . \DS . 'test' . \DS . \basename(\urlencode($_GET['test'])) . '.php';
    } else {
        $state_f = $state_d . \DS . $_['layout'] . ($_['i'] ? 's' : "") . '.php';
    }
    if (!isset($_GET['layout'])) {
        // Auto-detect layout type
        if ($f) {
            $_['layout'] = \is_dir($f) ? 'folder' : 'file';
            // Update data
            $GLOBALS['_'] = $_;
            // Auto-set layout type
            $state_f = $state_d . \DS . $_['layout'] . ($_['i'] ? 's' : "") . '.php';
        }
        // Manually set layout type based on file path
        foreach (\array_reverse(\step($_['path'], '/')) as $v) {
            (function($v) use(&$state_f) {
                if (\is_file($v)) {
                    extract($GLOBALS, \EXTR_SKIP);
                    // Update data and require
                    require ($state_f = $v);
                }
            })($state_d . \DS . 'file' . ($_['i'] ? 's' : "") . \DS . $v . '.php');
        }
        // Update data
        $_ = $GLOBALS['_'];
    }
    foreach ($GLOBALS['X'][1] as $v) {
        \is_file($v = \Path::F($v) . \DS . 'panel.php') && (function($v) {
            extract($GLOBALS, \EXTR_SKIP);
            require $v;
        })($v);
    }
    // Update data
    $_ = $GLOBALS['_'];
    // Define lot with no filter
    (function($state_f, $_) {
        extract($GLOBALS, \EXTR_SKIP);
        $_['lot'] = \array_replace_recursive((array) (\is_file($state_f) ? require $state_f : []), $_['lot'] ?? []);
        // Update data
        $GLOBALS['_'] = $_;
    })($state_f, $_);
    // Filter by status
    \is_file($v = __DIR__ . \DS . 'user' . \DS . $user['status'] . '.php') && (function($v) {
        extract($GLOBALS, \EXTR_SKIP);
        require $v;
    })($v);
    // Update data
    $_ = $GLOBALS['_'];
    // Filter by route function
    $_['form'] = \e($GLOBALS['_' . ($_SERVER['REQUEST_METHOD'] ?? 'GET')] ?? []);
    $GLOBALS['_']['form'] = $_['form'];
    if ($route) {
        \fire($route, [$_], $this);
    }
    // Update data
    $_ = $GLOBALS['_'];
    // Filter by hook
    $_ = \Hook::fire('_', [$_]);
    // Update data
    $GLOBALS['_'] = $_;
    if (isset($_['form']['token'])) {
        $hooks = \map(\step($_['layout']), function($hook) use($_) {
            return 'do.' . $hook . '.' . ([
                'g' => 'get',
                'l' => 'let',
                's' => 'set'
            ][$_['task']] ?? '?');
        });
        foreach (\array_reverse($hooks) as $hook) {
            if ($r = \Hook::fire($hook, [$_, $_['form']])) {
                $_ = $r;
            }
        }
    } else {
        // Missing `<input name="token">`
    }
    if (!empty($_['alert'])) {
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
        if (isset($_['form']['token'])) {
            \Guard::kick($url->clean . $url->i . $url->query('&', [
                'token' => false
            ]) . $url->hash);
        }
    }
    \State::set('[layout].layout:' . $_['layout'], true);
    $n = \ltrim($_['chops'][0] ?? "", '_.-');
    // Update data
    $GLOBALS['_'] = $_;
    $GLOBALS['t'][] = \i('Panel');
    $GLOBALS['t'][] = isset($_['path']) ? \i('x' === $n ? 'Extension' : \To::title($n)) : null;
    $this->view('panel');
}

\Route::set($_['/'] . '/*', 200, __NAMESPACE__ . "\\route", 20);
