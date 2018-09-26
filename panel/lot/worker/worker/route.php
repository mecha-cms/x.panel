<?php

Hook::set('on.ready', function() use($panel, $url) {
    $p = $panel->{'$'};
    if (strpos($url->path, $p . '/::') === 0) {
        Asset::reset();
        Route::reset();
    }
    Route::set([
        $p . '/::%s%::/%*%/%i%',
        $p . '/::%s%::/%*%'
    ], function($act = 'g', $path = "", $step = null) {
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
    Route::set($p . '/gate', function() {
        $path = str_replace('/', DS, HTTP::post('path'));
        $name = basename(HTTP::post('name'));
        $directory = str_replace('/', DS, HTTP::post('directory'));
        $consent = HTTP::post('consent', 0600);
        $content = HTTP::post('file.content');
        File::set($content)->saveTo(LOT . DS . rtrim($path . DS . $directory, DS) . DS . $name, $consent);
    }, 0);
}, 0);