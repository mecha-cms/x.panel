<?php namespace _\lot\x\panel;

function route() {
    if (\Request::is('Get')) {
        if (!\Is::user()) {
            \Guard::kick("");
        }
        extract($GLOBALS, \EXTR_SKIP);
        $GLOBALS['t'][] = \i('Panel');
        $f = $_['f'];
        // Redirect if file already exists
        if ('s' === $_['task'] && $f && \is_file($f)) {
            \Alert::info('File %s already exists.', '<code>' . \_\lot\x\panel\h\path($f) . '</code>');
            \Guard::kick(\str_replace('::s::', '::g::', $url->current));
        }
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
        if (
            // Trying to get file that does not exist
            'g' === $_['task'] && !$f ||
            // Trying to set file from a folder that does not exist
            's' === $_['task'] && (!$f || !\is_dir($f))
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
        $n = \ltrim($_['chops'][0], '_.-');
        $GLOBALS['t'][] = isset($_['path']) ? \i('x' === $n ? 'Extension' : \ucfirst($n)) : null;
        $this->view('panel');
    }
}

\Route::set($_['/'] . '*', 200, __NAMESPACE__ . "\\route", 20);
