<?php

$state = Extend::state(__DIR__);

if ($url->path === $state['path'] || strpos('/' . $url->path . '/', '/' . $state['path'] . '/') === 0) {
    Asset::reset();
}

Panel::set('page.type.HTML', 'HTML');

foreach (glob(EXTEND . DS . '*' . DS . '__index.php') as $v) {
    require $v;
}

foreach (glob(EXTEND . DS . 'plugin' . DS . 'lot' . DS . 'worker' . DS . '*' . DS . '__index.php') as $v) {
    require $v;
}

Route::set([$state['path'] . '/:%*%/%i%', $state['path'] . '/:%*%'], function($path, $step = false) use($state) {
    $chops = explode('/', $path);
    if (!$task = File::exist(__DIR__ . DS . 'lot' . DS . 'worker' . DS . $chops[0] . '.php')) {
        Shield::abort();
    }
    $shield_folder = __DIR__ . DS . 'lot' . DS . 'shield' . DS . $state['shield'];
    require $task;
    $s = __DIR__ . DS . 'lot' . DS . 'asset' . DS . 'css' . DS;
    Asset::set([
        $s . 'panel.min.css',
        $s . 'panel.code-mirror.min.css',
        $s . 'panel.t-i-b.min.css'
    ], [
        10,
        11,
        12
    ]);
    $s = __DIR__ . DS . 'lot' . DS . 'asset' . DS . 'js' . DS;
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
    if ($fn = File::exist($shield_folder . DS . 'index.php')) require $fn;
    Lot::set([
        'chops' => $chops,
        'shield_folder' => $shield_folder
    ]);
    Hook::set('shield.output', function($content) {
        $s = [];
        foreach (get_defined_constants(true)['user'] as $k => $v) {
            $s[] = '$.' . $k . '=' . s(json_encode($v)) . ';';
        }
        return str_replace('</body>', '<script>!function($){' . implode("", $s) . '$.Language=' . json_encode(Language::get()) . '}(Panel);</script></body>', $content);
    }, 1);
    Shield::attach($shield_folder . DS . ($step === false ? 'page.php' : 'pages.php'));
}, 1);