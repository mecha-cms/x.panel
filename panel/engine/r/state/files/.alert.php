<?php

// `http://127.0.0.1/panel/::g::/.alert/1`
$GLOBALS['_']['content'] = $_['content'] = 'page';

$lot = require __DIR__ . DS . '..' . DS . $_['content'] . 's.php';

// Hide all button(s)
$lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['hidden'] = true;

if (isset($lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'])) {
    foreach ($lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'] as $k => &$v) {
        $page = new Page($k);
        $v['url'] = $page['link'];
        $v['image'] = false;
        $v['tasks']['enter']['hidden'] = true;
        $v['tasks']['s']['hidden'] = true;
        $v['tasks']['g']['hidden'] = true;
        unset($v['link']);
    }
}

return $lot;