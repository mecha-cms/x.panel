<?php

Config::set('panel.v.' . $__chops[0] . '.as', $config->shield);

Hook::set('panel.a.' . $__chops[0], function($__a, $__v) use($language, $__chops) {
    if (file_exists(LOT . DS . $__chops[0] . DS . $__v[0]->slug . DS . 'state' . DS . 'config.php')) {
        $__a = ['state' => [$language->setting, $__a['edit'][1] . '/state/config.php']] + $__a;
    }
    $__a['edit'][0] = $language->open;
    return $__a;
});

require __DIR__ . DS . 'extend.php';