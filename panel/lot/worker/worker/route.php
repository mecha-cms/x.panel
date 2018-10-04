<?php

Hook::set('on.ready', function() {
    extract(Lot::get(null, []));
    $id = $panel->id;
    $r = $panel->r;
    $v = $panel->v;
    if (strpos($url->path, $r . '/::') === 0) {
        Asset::reset();
        Route::reset();
    }
    Route::set([
        $r . '/::%s%::/%*%/%i%',
        $r . '/::%s%::/%*%'
    ], function($c = 'g', $path = "", $step = null) use($id, $r, $v) {
        extract(Lot::get(null, []));
        Config::reset('is.error');
        // Prevent directory traversal attack <https://en.wikipedia.org/wiki/Directory_traversal_attack>
        $path = str_replace('../', "", urldecode($path));
        if ($f = File::exist(LOT . DS . $path)) {
            Config::set('trace', $trace = new Anemon([$language->{$id}, $site->title], ' &#x00B7; '));
            $error = false;
            if ($step !== null) {
                if ($step !== 1 && !glob($f . DS . '*', GLOB_NOSORT)) {
                    $error = true;
                }
                if ($f = File::exist(LOT . DS . $path . DS . $step)) {
                    $GLOBALS['URL']['path'] .= '/' . $step;
                    $GLOBALS['URL']['clean'] .= '/' . $step;
                    $GLOBALS['URL']['i'] = null;
                }
            }
        } else {
            Config::set('trace', new Anemon([$language->error, $site->title], ' &#x00B7; '));
            $error = true;
        }
        HTTP::status($error ? 404 : 200);
        Lot::set([
            'desk' => panel\desk(panel\_config([], 'desk'), $id),
            'error' => $error,
            'nav' => !HTTP::is('get', 'nav') || HTTP::get('nav') ? panel\nav(panel\_config([], 'nav'), $id) : ""
        ]);
        Shield::attach(__DIR__ . DS . 'shield.php');
    }, 0);
}, 0);