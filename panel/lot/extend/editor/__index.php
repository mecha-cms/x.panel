<?php

Hook::set('on.panel.ready', function() use($config, $language, $__chops) {
    if (!Config::get('page.editor', "")) return;
    // Load the asset(s)…
    $__s = __DIR__ . DS . 'lot' . DS . 'asset' . DS;
    Asset::set([
        $__s . 'css' . DS . 'c-m.min.css',
        $__s . 'css' . DS . 'c-m.fire.min.css',
        $__s . 'js' . DS . 'c-m.min.js',
        $__s . 'js' . DS . 'c-m.addon.min.js',
        $__s . 'js' . DS . 'c-m.mode.min.js',
        $__s . 'js' . DS . 'c-m.fire.min.js'
    ], [
        10.2,
        10.3,
        10.2,
        10.21,
        10.22,
        10.3
    ]);
    $__CM = new State(File::open(__DIR__ . DS . 'lot' . DS . 'state' . DS . 'config.php')->import());
    if ($__CM->theme && $__f = File::exist($__s . 'css' . DS . 'theme' . DS . $__CM->theme . '.min.css')) {
        Asset::set($__f, 10.21);
    }
    // Add configuration tab for this extension…
    if ($__chops[0] === 'state' && (!isset($__chops[1]) || $__chops[1] === 'config')) {
        $__themes = $__modes = $__addons = [];
        foreach (glob($__s . 'css' . DS . 'theme' . DS . '*.min.css', GLOB_NOSORT) as $__v) {
            $__v = basename($__v, '.min.css');
            $__themes[$__v] = isset($language->__->panel->CM->theme->{$__v}) ? $language->__->panel->CM->theme->{$__v} : (isset($language->{$__v}) ? $language->{$__v} : $__v . '.css');
        }
        foreach (glob($__s . 'js' . DS . 'mode' . DS . '*.min.js', GLOB_NOSORT) as $__v) {
            $__v = basename($__v, '.min.js');
            $__modes[$__v] = isset($language->__->panel->CM->mode->{$__v}) ? $language->__->panel->CM->mode->{$__v} : (isset($language->{$__v}) ? $language->{$__v} : $__v . '.js');
        }
        foreach (glob($__s . 'js' . DS . 'addon' . DS . '*', GLOB_ONLYDIR | GLOB_NOSORT) as $__v) {
            $__v = basename($__v);
            $__addons[$__v] = isset($language->__->panel->CM->addon->{$__v}) ? $language->__->panel->CM->addon->{$__v} : (isset($language->{$__v}) ? $language->{$__v} : To::title($__v));
        }
        asort($__themes);
        asort($__modes);
        asort($__addons);
        $__page = new Page(File::exist([
            __DIR__ . DS . 'about.' . $config->language . '.page',
            __DIR__ . DS . 'about.page'
        ]));
        Config::set('panel.m.t.editor', [
            'legend' => $__page->title,
            'description' => $__page->content . ($__page->link ? '<p>' . HTML::a($language->link . ' &#x21E2;', $__page->link, true, ['rel' => 'nofollow']) . '</p>' : ""),
            'content' => [
                'e[theme]' => [
                    'key' => 'theme',
                    'type' => 'select',
                    'value' => $__CM->theme,
                    'values' => ['!' => '&#x2716;'] + $__themes,
                    'stack' => 10
                ],
                'e[mode]' => [
                    'key' => 'mode',
                    'type' => 'toggle[]',
                    'title' => $language->languages,
                    'value' => (array) $__CM->mode,
                    'values' => $__modes,
                    'stack' => 20
                ],
                'e[addon]' => [
                    'key' => 'extension',
                    'type' => 'toggle[]',
                    'title' => $language->extensions,
                    'value' => (array) $__CM->addon,
                    'values' => $__addons,
                    'stack' => 30
                ],
                'e[o]' => [
                    'key' => 'o',
                    'type' => 'toggle[]',
                    'title' => $language->options,
                    'value' => array_map(function($__v) {
                        return is_array($__v) ? json_encode($__v) : $__v;
                    }, require __DIR__ . DS . 'lot' . DS . 'state' . DS . 'c.php'),
                    'values' => array_replace_recursive((array) $language->{'-o_editor'}->{'code-mirror'}, [
                        'matchTags' => [1 => '{"bothTags":1}'],
                        'autoCloseTags' => [1 => '{"whenClosing":1,"whenOpening":1,"dontCloseTags":["area","base","br","col","command","embed","hr","img","input","keygen","link","meta","param","source","track","wbr"],"indentTags":["blockquote","body","div","dl","fieldset","form","frameset","h1","h2","h3","h4","h5","h6","head","html","object","ol","select","table","tbody","tfoot","thead","tr","ul"]}']
                    ]),
                    'stack' => 40
                ]
            ],
            'stack' => 20.1
        ]);
    }
}, 1);

if ($__chops[0] === 'state' && (!isset($__chops[1]) || $__chops[1] === 'config') && $__r = Request::post('e')) {
    // Merge the asset(s)…
    $__s = __DIR__ . DS . 'lot' . DS . 'asset' . DS;
    $__assets = "";
    if (!empty($__r['addon'])) {
        foreach ((array) $__r['addon'] as $__v) {
            foreach (glob($__s . 'js' . DS . 'addon' . DS . $__v . DS . '*.min.js', GLOB_NOSORT) as $__vv) {
                $__assets .= file_get_contents($__vv);
            }
        }
        File::write($__assets)->saveTo($__s . 'js' . DS . 'c-m.addon.min.js');
    }
    $__assets = "";
    if (!empty($__r['mode'])) {
        foreach ((array) $__r['mode'] as $__v) {
            $__assets .= File::open($__s . 'js' . DS . 'mode' . DS . $__v . '.min.js')->read();
        }
        File::write($__assets)->saveTo($__s . DS . 'js' . DS . 'c-m.mode.min.js');
    }
    // Save setting(s)…
    if (!Message::$x) {
        if (isset($__r['o'])) {
            if ($__r['theme'] !== "") {
                $__r['o']['theme'] = $__r['theme'];
            } else {
                unset($__r['theme']);
            }
            File::export($__r['o'])->saveTo(__DIR__ . DS . 'lot' . DS . 'state' . DS . 'c.php', 0600);
            unset($__r['o']);
        }
        File::export($__r)->saveTo(__DIR__ . DS . 'lot' . DS . 'state' . DS . 'config.php', 0600);
        Request::reset('post', 'e');
        Message::success('update', [$language->setting, '<strong>' . $language->editor . '</strong>']);
    }
}

// Load setting(s)…
Config::set('panel.c.js.CM', File::open(__DIR__ . DS . 'lot' . DS . 'state' . DS . 'c.php')->import());