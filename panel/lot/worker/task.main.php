<?php

Route::set([$state['path'] . '/::%s%::/%*%/%i%', $state['path'] . '/::%s%::/%*%'], function($sgr, $path, $step = 1) use($state) {
    extract(Lot::get(null, []));
    $chops = explode('/', $path);
    if (!$task = File::exist(__DIR__ . DS . $chops[0] . '.php')) {
        Shield::abort();
    }
    $site->type = 'page';
    $shield_path = PANEL . DS . 'lot' . DS . 'shield' . DS . $state['shield'];
    require __DIR__ . DS . 'task.tunnel.php';
    require $task;
    $s = PANEL . DS . 'lot' . DS . 'asset' . DS . 'css' . DS;
    Asset::set([
        $s . 'panel.min.css',
        $s . 'panel.code-mirror.min.css',
        $s . 'panel.t-i-b.min.css'
    ], [
        10,
        11,
        12
    ]);
    $s = PANEL . DS . 'lot' . DS . 'asset' . DS . 'js' . DS;
    Asset::set([
        $s . 'panel.min.js',
        $s . 'panel.code-mirror.min.js',
        $s . 'panel.t-i-b.min.js',
        $s . 'panel.fire.min.js'
    ], [
        10,
        11,
        12
    ]);
    if ($fn = File::exist($shield_path . DS . 'index.php')) require $fn;
    Lot::set([
        'state' => o($state),
        'sgr' => $sgr,
        'chops' => $chops,
        'shield_path' => $shield_path
    ]);
    Hook::set('shield.output', function($content) {
        $s = [];
        foreach (get_defined_constants(true)['user'] as $k => $v) {
            $s[] = '$.' . $k . '=' . s(json_encode($v)) . ';';
        }
        return str_replace('</body>', '<script>!function($){' . implode("", $s) . '$.Language=' . json_encode(Language::get()) . '}(Panel);</script></body>', $content);
    }, 1);
    $extends = [];
    foreach (glob(EXTEND . DS . '*' . DS . '__index.php') as $v) {
        $s = Path::D($v) . DS;
        $extends[$v] = (float) File::open([$s . '__index.stack', $s . 'index.stack'])->get(0, 10);
    }
    asort($extends);
    foreach (array_keys($extends) as $extend) require $extend;
    $plugins = [];
    foreach (glob(EXTEND . DS . 'plugin' . DS . 'lot' . DS . 'worker' . DS . '*' . DS . '__index.php') as $v) {
        $s = Path::D($v) . DS;
        $plugins[$v] = (float) File::open([$s . '__index.stack', $s . 'index.stack'])->get(0, 10);
    }
    asort($plugins);
    foreach (array_keys($plugins) as $plugin) require $plugin;
    Shield::attach($shield_path . DS . $site->type . '.php');
}, 1);