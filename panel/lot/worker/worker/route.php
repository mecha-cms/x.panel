<?php

Hook::set('on.ready', function() use($panel, $url) {
    $r = $panel->r;
    if (strpos($url->path, $r . '/::') === 0) {
        Asset::reset();
        Route::reset();
    }
    Route::set([
        $r . '/::%s%::/%*%/%i%',
        $r . '/::%s%::/%*%'
    ], function($c = 'g', $path = "", $step = null) {
        extract(Lot::get(null, []));
        // Prevent directory traversal attack <https://en.wikipedia.org/wiki/Directory_traversal_attack>
        $path = str_replace('../', "", urldecode($path));
        if ($f = File::exist(LOT . DS . $path)) {
            if ($step !== null && $f = File::exist(LOT . DS . $path . DS . $step)) {
                $GLOBALS['URL']['path'] .= '/' . $step;
                $GLOBALS['URL']['clean'] .= '/' . $step;
                $GLOBALS['URL']['i'] = null;
            }
            Config::set('is', [
                'error' => false,
                'file' => is_file($f) ? $f : false,
                'files' => is_dir($f) ? $f : false
            ]);
        } else {
            panel\error(404);
        }
        Config::set('trace', new Anemon([$language->{$panel->id}, $config->title], ' &#x00B7; '));
        Shield::attach(__DIR__ . DS . '..' . DS . '..' . DS . 'shield' . DS . $panel->v . '.php');
    }, 0);
}, 0);