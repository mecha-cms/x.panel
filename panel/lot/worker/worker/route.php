<?php

Hook::set('start', function() {

    extract(Lot::get());

    $id = $panel->id;
    $r = $panel->r;
    $v = $panel->v;

    if (strpos($url->path, $r . '/::') === 0) {
        Asset::reset(); // Remove all asset(s)
        Route::reset(['%A%/%I%', '%A%', ""]); // Remove all route(s) from `page` extension
        Shield::$config['id'] = X; // Disable current shield asset(s)
        $asset = __DIR__ . DS . '..' . DS . '..' . DS . 'asset' . DS;
        Asset::set($asset . 'js' . DS . 'zepto.min.js', 9);
        Asset::set($asset . 'js' . DS . 'code-mirror.min.js', 9.1);
        Asset::set($asset . 'js' . DS . 'code-mirror' . DS . 'display.min.js', 9.11);
        Asset::set($asset . 'js' . DS . 'code-mirror' . DS . 'edit.min.js', 9.11);
        Asset::set($asset . 'js' . DS . 'code-mirror' . DS . 'mode.min.js', 9.11);
        $f = __DIR__ . DS . '..' . DS . '..' . DS;
        $t = glob($f . 'state' . DS . '*.php', GLOB_NOSORT);
        $t = array_reduce(array_map(function($v) {
            return filemtime($v);
        }, $t), function($a, $b) {
            return $a + $b;
        });
        $t += filemtime(__FILE__);
        if ($f = File::exist($f . 'language' . DS . $config->language . '.page')) {
            $t += filemtime($f);
        }
        $t = abs(crc32($t . $user->token)); // Smart cache updater
        if ($style = (array) ($panel->state->style ?? [])) {
            if (!empty($style['width'])) {
                $width = $style['width'];
                Asset::style('.desk{max-width:' . (is_int($width) ? $width . 'px' : $width) . '}', 10, ['media' => 'screen']);
            }
        }
        Asset::set($url . '/' . $r . '/::g::/-/asset.js', 10, [
            'src' => function($src) use($t) {
                return $src . '?v=' . $t;
            }
        ]);
        $skin = $panel->state->skin ?? null;
        Asset::set($asset . 'css' . DS . 'panel.min.css', 10);
        Asset::set($asset . 'css' . DS . 'code-mirror.min.css', 10.1);
        if (isset($skin) && $skin !== "") {
            Asset::set($asset . 'css' . DS . 'panel' . DS . $skin . '.min.css', 10.2);
        }
        Asset::set($asset . 'js' . DS . 'panel.min.js', 10);
    }

    Hook::set('asset:body', function($body) use($user) {
        $url = $GLOBALS['URL'];
        unset($url['user'], $url['pass']);
        $out = [
            '$token' => $user->token,
            '$url' => $url,
            '$u_r_l' => $url
        ];
        return '<script>window.panel=' . json_encode($out) . ';</script>' . $body;
    }, 0);

    Route::set([
        $r . '/::%s%::/%*%/%i%',
        $r . '/::%s%::/%*%'
    ], function($c = 'g', $path = "", $step = null) use($id, $r, $v) {
        extract(Lot::get());
        Config::reset('is.error');
        // Prevent directory traversal attack <https://en.wikipedia.org/wiki/Directory_traversal_attack>
        $path = str_replace('../', "", urldecode($path));
        $error = Config::get('panel.error');
        if ($f = File::exist(LOT . DS . $path)) {
            Config::set('trace', $trace = new Anemon([$language->{$error ? 'error' : str_replace('.', "\\.", $id)}, $config->title], ' &#x00B7; '));
            $error = false;
            if ($step !== null) {
                if ($step !== 1 && !glob($f . DS . '*', GLOB_NOSORT)) {
                    $error = true;
                }
            }
        } else {
            Config::set('trace', new Anemon([$language->error, $config->title], ' &#x00B7; '));
            $error = true;
        }
        HTTP::status($error ? 404 : 200);
        foreach (['body', 'footer', 'header'] as $v) {
            if (HTTP::is('get', $v) && !HTTP::get($v)) {
                Config::reset('panel.desk.' . $v);
            }
        }
        $desk = fn\panel\desk((array) Config::get('panel.desk', true), $id);
        $icons = $menus = $nav = "";
        if (HTTP::is('get', 'nav') && !HTTP::get('nav')) {
            Config::reset('panel.nav');
        } else {
            $nav = fn\panel\nav((array) Config::get('panel.nav', true), $id);
        }
        if ($error) {
            Config::set('panel.error', $error);
        }
        foreach ((array) Config::get('panel.+.menu', true) as $k => $v) {
            $menus .= fn\panel\menus($v, $k, [
                'data[]' => ['js-enter' => '#js:' . $k]
            ], 1);
        }
        if (!empty($GLOBALS['SVG'])) {
            $icons .= '<svg id="svg:panel" xmlns="http://www.w3.org/2000/svg" display="none">';
            foreach ($GLOBALS['SVG'] as $k => $v) {
                $icons .= '<symbol id="i:' . dechex(crc32($k . $v)) . '" viewBox="' . (is_string($v) ? $v : '0 0 24 24') . '"><path d="' . $k . '"></path></symbol>';
            }
            $icons .= '</svg>';
        }
        Lot::set([
            'desk' => $desk,
            'icons' => $icons,
            'menus' => $menus,
            'nav' => $nav
        ]);
        return Shield::attach(__DIR__ . DS . 'shield.php');
    }, 10);

    Route::set($r . '/::g::/-/asset.js', function() {
        extract(Lot::get());
        $i = 60 * 60 * 24 * 30 * 12; // 1 Year
        HTTP::type('application/javascript')->header([
            'Pragma' => 'private',
            'Cache-Control' => 'private, max-age=' . $i,
            'Expires' => gmdate('D, d M Y H:i:s', time() + $i) . ' GMT'
        ]);
        foreach ([
            '$language' => $language->get(),
            '$panel' => $panel->state,
            '$svg' => $config->get('panel.+.svg'),
            '$user' => [
                '$' => $user->{'$'},
                'key' => $user->key,
                'status' => $user->status
            ]
        ] as $k => $v) {
            echo 'panel.' . $k . '=' . json_encode($v) . ';';
        }
        return;
    });

    $image_path = $r . '/::g::/-/%i%/%i%/%s%.%[gif,jpg,jpeg,png]%';
    Route::set($image_path, function($width, $height, $color, $x) {
        $i = 60 * 60 * 24 * 30 * 12; // 1 year
        // Handle invalid MIME type
        if ($x === 'jpg') {
            $x = 'jpeg';
        }
        // Handle invalid HEX color
        if (!ctype_xdigit($color) || strlen($color) !== 6) {
            $color = '000000'; // default to black
        }
        HTTP::type('image/' . $x)->header([
            'Pragma' => 'private',
            'Cache-Control' => 'private, max-age=' . $i,
            'Expires' => gmdate('D, d M Y H:i:s', time() + $i) . ' GMT'
        ]);
        $image = imagecreate($width, $height);
        $hash = str_split($color, 2);
        imagecolorallocate($image, hexdec($hash[0]), hexdec($hash[1]), hexdec($hash[2]));
        call_user_func('image' . $x, $image);
        imagedestroy($image);
        return;
    }, 9);

    Route::set($r . '/::g::/-/%I%/%S%.%[gif,jpg,jpeg,png]%', function($size, $color, $x) use($image_path) {
        return Route::fire($image_path, [$size, $size, $color, $x]);
    }, 9.1);

    Route::set($r . '/::g::/-/%S%.%[gif,jpg,jpeg,png]%', function($color, $x) use($image_path) {
        return Route::fire($image_path, [1, 1, $color, $x]);
    }, 9.2);

}, 2);