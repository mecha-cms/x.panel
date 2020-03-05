<?php

// `http://127.0.0.1/panel/::g::/.alert/1`
$GLOBALS['_']['layout'] = $_['layout'] = 'page';

$lot = require __DIR__ . DS . '..' . DS . $_['layout'] . 's.php';

// Hide all button(s)
$lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['hidden'] = true;

if (isset($lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'])) {
    foreach ($lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'] as $k => &$v) {
        $page = new Page($k);
        $link = $page->link;
        $v = '<alert type="' . c2f($page->type) . '">' . $page->title . '<br><small>' . _\lot\x\panel\h\w($page->description) . '</small><br><small>' . ($link ? '<a href="' . $url . $_['/'] . '/::f::/f7a17db4' . strtr($k, [
            LOT => "",
            DS => '/'
        ]) . $url->query('&amp;', [
            'kick' => strtr($link, [$url . '/' => ""]),
            'token' => $_['token']
        ]) . '">' . i('Action') . '</a> ' : "") . '<a href="' . $url . $_['/'] . '/::l::' . strtr($k, [
            LOT => "",
            DS => '/'
        ]) . $url->query('&amp;', [
            'token' => $_['token']
        ]) . '">' . i('Delete') . '</a></small></alert>';
    }
}

return $lot;
