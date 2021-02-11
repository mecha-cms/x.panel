<?php namespace _\lot\x\panel\route;

function __test($_) {
    extract($GLOBALS, \EXTR_SKIP);
    $tests = \is(\get_defined_functions(true)['user'], function($v) {
        return 0 === \strpos($v, "_\\lot\\x\\panel\\route\\__test\\");
    });
    $content = '<ul>';
    foreach ($tests as $test) {
        $path = \implode('/', \map(\explode("\\", \substr($test, 20)), function($v) {
            return \strtr(\p2f($v), ['__' => '.']);
        }));
        $content .= '<li><a href="' . $url . $_['/'] . '/::g::/' . $path . '" target="_blank"><code>' . $test . '</code></a></li>';
    }
    $content .= '</ul>';
    $_['title'] = 'Tests';
    $_['lot']['desk']['lot']['form']['lot'][1] = [
        'title' => 'Tests',
        'description' => 'List of the available control panel tests.',
        'type' => 'section',
        'content' => $content
    ];
    return $_;
}

require __DIR__ . \DS . 'route' . \DS . 'bar.php';
require __DIR__ . \DS . 'route' . \DS . 'content.php';
require __DIR__ . \DS . 'route' . \DS . 'fields.php';
require __DIR__ . \DS . 'route' . \DS . 'section.php';
require __DIR__ . \DS . 'route' . \DS . 'separator.php';
require __DIR__ . \DS . 'route' . \DS . 'tabs.php';
require __DIR__ . \DS . 'route' . \DS . 'title.php';
