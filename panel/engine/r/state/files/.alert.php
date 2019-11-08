<?php

// `http://127.0.0.1/panel/::g::/.alert/1`
$GLOBALS['_']['layout'] = $_['layout'] = 'page';

$lot = require __DIR__ . DS . '..' . DS . $_['layout'] . 's.php';

// Hide all button(s)
$lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['hidden'] = true;

if (isset($lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'])) {
    foreach ($lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'] as $k => &$v) {
        $page = new Page($k);
        $v = '<alert type="' . c2f($page->type) . '">' . $page->title . '<br><small>' . _\lot\x\panel\h\w($page->description) . '</small></alert>';
    }
}

return $lot;