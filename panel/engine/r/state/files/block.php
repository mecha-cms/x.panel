<?php

// `http://127.0.0.1/panel/::g::/block/1`
$lot = require __DIR__ . DS . '..' . DS . $_['layout'] . 's.php';
if (isset($lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'])) {
    $before = $url . $_['/'] . '::';
    foreach ($lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'] as $k => &$v) {
        $after = '::' . strtr($k, [
            LOT => "",
            DS => '/'
        ]);
        $v['tasks']['g']['url'] = $before . 'g' . $after . $url->query('&', ['layout' => 'data', 'tab' => false]) . $url->hash;
    }
}

$lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['file']['title'] = 'Block';
$lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['description'][1] = 'Block';
$lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['file']['url'] = $url . $_['/'] . '::s::' . $_['path'] . $url->query('&', ['layout' => 'data', 'tab' => false]) . $url->hash;

$lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['blob']['hidden'] = true;
$lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['folder']['hidden'] = true;

return $lot;