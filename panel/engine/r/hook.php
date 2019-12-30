<?php namespace _\lot\x\panel;

// Task
if (\is_file($_task = __DIR__ . \DS . 'task' . \DS . $_['task'] . '.php')) {
    require $_task;
}

function _() {
    extract($GLOBALS);
    $_state = __DIR__ . \DS . 'state' . \DS;
    if (\defined("\\DEBUG") && \DEBUG && isset($_GET['test'])) {
        $_lot = $_state . 'test' . \DS . \basename(\urlencode($_GET['test'])) . '.php';
    } else {
        $_lot = $_state . $_['layout'] . ($_['i'] ? 's' : "") . '.php';
        if (!isset($_GET['layout'])) {
            // Auto-detect layout type
            if ($_['f']) {
                $GLOBALS['_']['layout'] = $_['layout'] = \is_dir($_['f']) ? 'folder' : 'file';
                $_lot = $_state . $_['layout'] . ($_['i'] ? 's' : "") . '.php'; // Update data
            }
            // Manually set layout type based on file path
            foreach (\array_reverse(\step(\trim($_['path'], '/'), '/')) as $_path) {
                (function($_path) use(&$_lot) {
                    if (\is_file($_path)) {
                        extract($GLOBALS, \EXTR_SKIP);
                        $_lot = $_path; // Update data
                        require $_path;
                    }
                })($_state . 'file' . ($_['i'] ? 's' : "") . \DS . $_path . '.php');
            }
            $_ = $GLOBALS['_']; // Update data
        }
    }
    foreach ($GLOBALS['X'][1] as $_index) {
        \is_file($_f = \Path::F($_index) . \DS . 'panel.php') && (function($_f) {
            extract($GLOBALS, \EXTR_SKIP);
            require $_f;
        })($_f);
    }
    $_ = $GLOBALS['_']; // Update data
    (function($_lot) {
        extract($GLOBALS, \EXTR_SKIP);
        // Define lot with no filter
        $GLOBALS['_']['lot'] = $_['lot'] = \array_replace_recursive($_['lot'] ?? [], (array) (\is_file($_lot) ? require $_lot : []));
        // Filter by status
        \is_file($_f = __DIR__ . \DS . 'user' . \DS . $user['status'] . '.php') && (function($_f) {
            extract($GLOBALS, \EXTR_SKIP);
            require $_f;
        })($_f);
        $_ = $GLOBALS['_']; // Update data
        // Filter by path
        foreach (\array_values(\step(\trim($_['path']), '/')) as $_v) {
            \is_file($_f = __DIR__ . \DS . 'path' . \DS . \strtr($_v, '/', \DS) . '.php') && (function($_f) {
                extract($GLOBALS, \EXTR_SKIP);
                require $_f;
            })($_f);
        }
        // Filter by hook
        $_ = $GLOBALS['_'] = \Hook::fire('_', [$GLOBALS['_']]); // Update data
        $_form = \e($GLOBALS['_' . ($_SERVER['REQUEST_METHOD'] ?? 'GET')] ?? []);
        if (isset($_form['token'])) {
            $_hooks = \map(\step($_['layout']), function($_hook) use($_) {
                return 'do.' . $_hook . '.' . ([
                    'g' => 'get',
                    'l' => 'let',
                    's' => 'set'
                ][$_['task']] ?? '/');
            });
            foreach (\array_reverse($_hooks) as $_hook) {
                if ($_r = \Hook::fire($_hook, [$_, $_form])) {
                    $_ = $GLOBALS['_'] = $_r;
                }
            }
        }
        if (!empty($_['alert'])) {
            foreach ((array) $_['alert'] as $_k => $_v) {
                foreach ((array) $_v as $_alert) {
                    $_alert = (array) $_alert;
                    \call_user_func("\\Alert::" . $_k, ...$_alert);
                }
            }
        }
        if (isset($_['kick'])) {
            \Guard::kick($_['kick']);
        } else {
            if (isset($_form['token'])) {
                \Guard::kick($url->clean . $url->i . $url->query('&', [
                    'token' => false
                ]) . $url->hash);
            }
        }
        \State::set('[layout].layout:' . $_['layout'], true);
    })($_lot);
}

\Hook::set('get', __NAMESPACE__ . "\\_", 20);
