<?php namespace _\lot\x\panel;

function route($form, $k) {
    if (!\Is::user()) {
        // TODO: Show 404 page to confuse URL guesser
        \Guard::kick("");
    }
    extract($GLOBALS, \EXTR_SKIP);
    $GLOBALS['t'][] = $language->panel;
    $n = \ltrim(\explode('/', $_['path'], 3)[1] ?? '?', '_.-');
    $GLOBALS['t'][] = isset($_['path']) ? $language->{$n === 'x' ? 'extension' : $n} : null;
    \Config::set([
        'has' => [
            'parent' => \substr_count($_['path'], '/') > 1,
        ],
        'is' => [
            'error' => false,
            'page' => !isset($_['i']),
            'pages' => isset($_['i'])
        ]
    ]);
    if ($_['task'] === 'g' && (
        !isset($_['f']) ||
        !\is_dir($_['f']) && isset($_['i'])
    )) {
        $this->status(404);
        $this->content(__DIR__ . \DS . 'content' . \DS . '404.php');
    }
    $this->content(__DIR__ . \DS . 'content' . \DS . 'panel.php');
}

\Route::set($_['//'] . '/*', 200, __NAMESPACE__ . "\\route", 1);