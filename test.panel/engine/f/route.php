<?php namespace x\panel\route;

function __test($_) {
    $_['status'] = 200;
    $_['title'] = 'Tests';
    // `http://127.0.0.1/panel/get/.test/*`
    if ('.test' !== $_['path']) {
        return $_;
    }
    // `http://127.0.0.1/panel/get/.test`
    \extract($GLOBALS, \EXTR_SKIP);
    $tests = \is(\get_defined_functions(true)['user'], static function($v) {
        return 0 === \strpos($v, "x\\panel\\route\\__test\\");
    });
    $content = '<ul>';
    foreach ($tests as $test) {
        $path = \implode('/', \map(\explode("\\", \substr($test, 14)), static function($v) {
            return \p2f($v);
        }));
        $content .= '<li><a href="' . \x\panel\to\link([
            'part' => 0,
            'path' => $path,
            'query' => null,
            'task' => 'get'
        ]) . '" target="_blank"><code>' . $test . '</code></a></li>';
    }
    $content .= '</ul>';
    $_['lot']['desk']['lot']['form']['lot'][1] = [
        'content' => $content,
        'description' => 'List of the available control panel tests.',
        'title' => 'Tests',
        'type' => 'section'
    ];
    return $_;
}