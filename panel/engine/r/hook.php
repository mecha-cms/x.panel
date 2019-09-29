<?php namespace _\lot\x\panel;

// Special case
if ($_['task'] === 'g' && $_['path'] === '/.state') {
    $GLOBALS['_']['content'] = $_['content'] = 'state';
}

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
        $_lot = $_state . $_['content'] . ($_['i'] > 0 ? 's' : "") . '.php';
        if (!isset($_GET['content'])) {
            // Auto-detect content type
            if ($_['f']) {
                $GLOBALS['_']['content'] = $_['content'] = \is_dir($_['f']) ? 'folder' : 'file';
                $_lot = $_state . $_['content'] . ($_['i'] > 0 ? 's' : "") . '.php'; // Update data
            }
            // Manually set content type based on file path
            foreach (\array_reverse(\step(\trim($_['path'], '/'), '/')) as $_path) {
                (function($_path) use(&$_lot) {
                    if (\is_file($_path)) {
                        extract($GLOBALS, \EXTR_SKIP);
                        $_lot = $_path; // Update data
                        require $_path;
                    }
                })($_state . 'file' . ($_['i'] > 0 ? 's' : "") . \DS . $_path . '.php');
            }
            $_ = $GLOBALS['_']; // Update data
        }
    }
    \State::set('[content].content:' . $_['content'], true);
    foreach ($GLOBALS['X'][1] as $_index) {
        \is_file($_f = \dirname($_index) . \DS . 'panel.php') && (function($_f) {
            extract($GLOBALS, \EXTR_SKIP);
            require $_f;
        })($_f);
    }
    (function($_lot) {
        extract($GLOBALS, \EXTR_SKIP);
        $GLOBALS['_']['lot'] = $_['lot'] = \array_replace_recursive($_['lot'] ?? [], (array) (\is_file($_lot) ? require $_lot : []));
        $_form = \e($GLOBALS['_' . ($_SERVER['REQUEST_METHOD'] ?? 'GET')] ?? []);
        if (isset($_form['token'])) {
            $_hooks = \map(\step($_['content']), function($_hook) use($_) {
                return 'do.' . $_hook . '.' . ([
                    'g' => 'get',
                    'l' => 'let',
                    's' => 'set'
                ][$_['task']] ?? '/');
            });
            foreach (\array_reverse($_hooks) as $_hook) {
                if ($_r = \Hook::fire($_hook, [$_, $_form])) {
                    $GLOBALS['_'] = $_ = $_r;
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
            if (!empty($_['kick'])) {
                \Guard::kick($_['kick']);
            }
        }
    })($_lot);
}

\Hook::set('start', __NAMESPACE__ . "\\_", 10);