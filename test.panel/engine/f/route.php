<?php namespace x\panel\route;

function __test($_) {
    extract($GLOBALS, \EXTR_SKIP);
    $tests = \is(\get_defined_functions(true)['user'], function($v) {
        return 0 === \strpos($v, "x\\panel\\route\\__test\\");
    });
    $content = '<ul>';
    foreach ($tests as $test) {
        $path = \implode('/', \map(\explode("\\", \substr($test, 14)), function($v) {
            return \p2f($v);
        }));
        $content .= '<li><a href="' . $_['/'] . '/::g::/' . $path . '" target="_blank"><code>' . $test . '</code></a></li>';
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

require __DIR__ . \DS . 'route' . \DS . 'alert.php';
require __DIR__ . \DS . 'route' . \DS . 'bar.php';
require __DIR__ . \DS . 'route' . \DS . 'content.php';
require __DIR__ . \DS . 'route' . \DS . 'description.php';
require __DIR__ . \DS . 'route' . \DS . 'fields.php';
require __DIR__ . \DS . 'route' . \DS . 'files.php';
require __DIR__ . \DS . 'route' . \DS . 'menu.php';
require __DIR__ . \DS . 'route' . \DS . 'pager.php';
require __DIR__ . \DS . 'route' . \DS . 'pages.php';
require __DIR__ . \DS . 'route' . \DS . 'section.php';
require __DIR__ . \DS . 'route' . \DS . 'separator.php';
require __DIR__ . \DS . 'route' . \DS . 'stacks.php';
require __DIR__ . \DS . 'route' . \DS . 'tabs.php';
require __DIR__ . \DS . 'route' . \DS . 'tasks.php';
require __DIR__ . \DS . 'route' . \DS . 'title.php';
require __DIR__ . \DS . 'route' . \DS . 'typography.php';