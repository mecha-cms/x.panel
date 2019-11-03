<?php namespace _\lot\x\panel;

function route($lot) {
    if (!\Is::user()) {
        \Guard::kick("");
    }
    extract($GLOBALS, \EXTR_SKIP);
    $GLOBALS['t'][] = \i('Panel');
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
    if ('g' === $_['task'] && (
        !isset($_['f']) ||
        !\is_dir($_['f']) && isset($_['i'])
    )) {
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
    $n = \ltrim($_['chops'][0], '_.-');
    $GLOBALS['t'][] = isset($_['path']) ? \i('x' === $n ? 'Extension' : \ucfirst($n)) : null;
    $this->view('panel');
}

\Route::set($_['/'] . '*', 200, __NAMESPACE__ . "\\route", 20);
