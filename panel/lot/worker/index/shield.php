<?php

Config::set('panel.v.' . $__chops[0] . '.as', $config->shield);

Hook::set($__chops[0] . '.image', function($__image, $__lot) {
    $__r = dirname($__lot['path']);
    $__d = $__r . DS . 'asset' . DS;
    $__n = DS . basename($__r) . '.';
    return To::url(File::exist([
        $__d . 'gif' . $__n . 'gif',
        $__d . 'jpg' . $__n . 'jpg',
        $__d . 'png' . $__n . 'png'
    ], $__image));
}, 0);

if (count($__chops) === 1) {
    $__query = HTTP::query([
        'token' => false,
        'r' => false
    ]);
    Hook::set('panel.a.' . $__chops[0], function($__a, $__v) use($config, $language, $__chops, $__query) {
        if (file_exists(LOT . DS . $__chops[0] . DS . $__v[0]->slug . DS . 'state' . DS . 'config.php')) {
            $__a = ['state' => [$language->setting, $__a['edit'][1] . '/state/config.php' . $__query]] + $__a;
        }
        // Prevent user to commit suicide…
        if (isset($__a['edit'][1]) && basename($__a['edit'][1]) === $config->shield) {
            unset($__a['reset']);
        }
        return $__a;
    }, 0);
} else if ($__command === 'r' && isset($__chops[1]) && $__chops[1] === $config->shield) {
    Shield::abort(406);
}

require __DIR__ . DS . 'extend.php';