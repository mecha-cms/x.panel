<?php namespace _\lot\x\panel;

function route($form, $k) {
    if (!\Is::user()) {
        // TODO: Show 404 page to confuse URL guesser
        \Guard::kick("");
    }
    extract($GLOBALS, \EXTR_SKIP);
    $GLOBALS['t'][] = $language->panel;
    $GLOBALS['t'][] = isset($PANEL['path']) ? $language->{\explode('/', $PANEL['path'], 3)[1]} : null;
    \Config::set([
        'has' => [
            'parent' => \substr_count($PANEL['path'], '/') > 1,
        ],
        'is' => [
            'error' => false,
            'page' => !isset($PANEL['i']),
            'pages' => isset($PANEL['i'])
        ]
    ]);
    if ($PANEL['task'] === 'g' && !isset($PANEL['file']['path'])) {
        $this->status(404);
        $this->content(__DIR__ . \DS . 'content' . \DS . '404.php');
    }
    $this->content(__DIR__ . \DS . 'content' . \DS . 'panel.php');
}

\Route::set($PANEL['//'] . '/*', __NAMESPACE__ . "\\route", 1);