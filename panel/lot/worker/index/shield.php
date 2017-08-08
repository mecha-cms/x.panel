<?php

Config::set('panel.v.' . $__chops[0] . '.as', $config->shield);

if (count($__chops) === 1) {
    $__query = HTTP::query([
        'token' => false,
        'force' => false
    ]);
    Hook::set('panel.a.' . $__chops[0], function($__a, $__v) use($language, $__chops, $__query) {
        if (file_exists(LOT . DS . $__chops[0] . DS . $__v[0]->slug . DS . 'state' . DS . 'config.php')) {
            $__a = ['state' => [$language->setting, $__a['edit'][1] . '/state/config.php' . $__query]] + $__a;
        }
        return $__a;
    }, 0);
}

require __DIR__ . DS . 'extend.php';