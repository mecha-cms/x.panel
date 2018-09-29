<?php

Hook::set('on.ready', function() {
    extract(Lot::get(null, []));
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
            Config::set('trace', $trace = new Anemon([$language->{$panel->id}, $site->title], ' &#x00B7; '));
            $error = false;
            if ($step !== null) {
                if ($step !== 1 && !glob(LOT . DS . $path . DS . '*', GLOB_NOSORT)) {
                    $error = true;
                }
                if ($f = File::exist(LOT . DS . $path . DS . $step)) {
                    $GLOBALS['URL']['path'] .= '/' . $step;
                    $GLOBALS['URL']['clean'] .= '/' . $step;
                    $GLOBALS['URL']['i'] = null;
                }
            }
        } else {
            Config::set('trace', $trace = new Anemon([$language->error, $site->title], ' &#x00B7; '));
            $error = true;
        }
        $nav = panel\nav(panel\_config([], 'nav'), $panel->id);
        $desk = panel\desk(panel\_config([], 'desk'), $panel->id);
        HTTP::status($error ? 404 : 200);
        echo '<!DOCTYPE html>';
        echo '<html lang="' . $site->language . '" dir="' . $site->direction . '" class="' . ($error ? 'is-error error-404' : 'is-' . $panel->v) . '">';
        echo '<head>';
        echo '<meta charset="' . $site->charset . '">';
        echo '<meta name="viewport" content="width=device-width">';
        echo '<title>' . To::text($trace) . '</title>';
        echo '<link href="' . $url . '/favicon.ico" rel="shortcut icon">';
        echo str_replace('"stylesheet"', '"stylesheet/less"', Asset::css(EXTEND . '/panel/lot/asset/less/panel.less'));
        echo Asset::js(EXTEND . '/panel/lot/asset/index.js');
        echo '</head>';
        echo '<body spellcheck="false">';
        echo $message;
        echo $nav;
        echo $panel->v === 'file' ? '<form class="form m0 p0" action="' . HTTP::query(['token' => $token]) . '" method="post" enctype="multipart/form-data">' : "";
        echo $error ? '<p class="m0 p2">&#x0CA0;&#x005F;&#x0CA0;</p>' : $desk;
        echo $panel->v === 'file' ? '</form>' : "";
        echo '<footer></footer>';
        foreach ((array) Config::get('panel.$.menus', [], true) as $k => $v) {
            echo panel\menus($v, $k, [
                'data[]' => ['js-enter' => '#js:' . $k]
            ]);
        }
        echo Asset::js(EXTEND . '/panel/lot/asset/js/panel.js');
        echo '</body>';
        echo '</html>';
        exit;
    }, 0);
}, 0);