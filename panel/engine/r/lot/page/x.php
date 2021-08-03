<?php

$_ = require __DIR__ . DS . '..' . DS . 'index.php';

$bounds = [];

foreach (g(LOT . DS . 'x', 'page', 1) as $k => $v) {
    if ('about.page' !== basename($k)) {
        continue;
    }
    $p = new Page($k);
    $title = strip_tags((string) ($p->title ?? ""));
    $key = strtr(x\panel\from\path(dirname($k)), [
        "\\" => '/'
    ]);
    foreach ((array) $p['use'] as $kk => $vv) {
        $bounds[strtr($kk, [
            "\\" => '/'
        ])][$key] = $title;
    }
}

$bound = $bounds[x\panel\from\path(LOT . DS . 'x' . DS . $_['chop'][1])] ?? [];

if (!empty($bound)) {
    asort($bound);
    $_['lot']['desk']['lot']['form']['lot'][2]['lot']['fields']['lot'][0]['lot']['tasks']['lot']['l']['skip'] = true;
}

if ('post' !== $_['form']['type'] && 'php' === pathinfo($_['f'], PATHINFO_EXTENSION)) {
    $_['alert']['warning'][md5(__FILE__)] = 'Unless you are very familiar with what you are doing, I advise you not to edit the extension files directly through the control panel interface. It might prevent your page from loading completely when you make some mistakes.';
}

return $_;
