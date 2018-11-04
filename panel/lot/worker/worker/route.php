<?php

Hook::set('on.ready', function() {

    extract(Lot::get(null, []));

    $id = $panel->id;
    $r = $panel->r;
    $v = $panel->v;

    if (strpos($url->path, $r . '/::') === 0) {
        Asset::reset();
        Route::reset();
        $asset = __DIR__ . DS . '..' . DS . '..' . DS . 'asset' . DS;
        if (defined('DEBUG') && DEBUG && Extend::exist('less')) {
            Asset::set($asset . 'less' . DS . 'panel.less', 9);
            Asset::set($asset . 'js' . DS . 'panel.js', 9, [
                'src' => function($src) use($token) {
                    $q = strpos($src, '?') !== false ? '&' : '?';
                    return $src . $q . 'token=' . $token;
                }
            ]);
        } else {
            Asset::set($asset . 'css' . DS . 'panel.min.css', 9);
            Asset::set($asset . 'js' . DS . 'panel.min.js', 9, [
                'src' => function($src) use($token) {
                    $q = strpos($src, '?') !== false ? '&' : '?';
                    return $src . $q . 'token=' . $token;
                }
            ]);
        }
    }

    // TODO
    Hook::set('asset:head', function($content) use($language) {
        return '<script>window.$language=' . json_encode($language->get()) . ';</script>' . $content;
    });

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
        foreach (['body', 'footer', 'header'] as $v) {
            if (HTTP::is('get', $v) && !HTTP::get($v)) {
                Config::reset('panel.desk.' . $v);
            }
        }
        if (HTTP::is('get', 'nav') && !HTTP::get('nav')) {
            Config::reset('panel.nav');
            $nav = "";
        } else {
            $nav = fn\panel\nav((array) Config::get('panel.nav', [], true), $id);
        }
        if ($error) {
            Config::set('panel.error', $error);
        }
        Lot::set([
            'desk' => fn\panel\desk((array) Config::get('panel.desk', [], true), $id),
            'nav' => $nav
        ]);
        return Shield::attach(__DIR__ . DS . 'shield.php');
    }, 10);

    $image_path = $r . '/::g::/%i%/%i%/%s%.%[gif,jpg,jpeg,png]%';
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
    Route::set($r . '/::g::/%i%/%s%.%[gif,jpg,jpeg,png]%', function($size, $color, $x) use($image_path) {
        return Route::fire($image_path, [$size, $size, $color, $x]);
    }, 9.1);
    Route::set($r . '/::g::/%s%.%[gif,jpg,jpeg,png]%', function($color, $x) use($image_path) {
        return Route::fire($image_path, [1, 1, $color, $x]);
    }, 9.2);
}, 0);