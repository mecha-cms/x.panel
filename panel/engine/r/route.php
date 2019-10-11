<?php namespace _\lot\x\panel;

function route($lot) {
    if (!\Is::user()) {
        \Guard::kick("");
    }
    extract($GLOBALS, \EXTR_SKIP);
    $GLOBALS['t'][] = $language->panel;
    \State::set([
        'has' => [
            'parent' => \count($_['chop']) > 1,
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
        $GLOBALS['t'][] = $language->isError;
        \State::set([
            '[content]' => ['content:' . $_['content'] => false],
            'is' => [
                'error' => 404
            ]
        ]);
        $this->status(404);
        $this->content(__DIR__ . \DS . 'content' . \DS . '404.php');
    }
    $n = \ltrim($_['chop'][0], '_.-');
    $GLOBALS['t'][] = isset($_['path']) ? $language->{$n === 'x' ? 'extension' : $n} : null;
    $this->content(__DIR__ . \DS . 'content' . \DS . 'panel.php');
}

\Route::set($_['/'] . '*', 200, __NAMESPACE__ . "\\route", 20);