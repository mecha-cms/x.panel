<?php

call_user_func(function() use($config, $language) {

    $__editor = isset($config->page->editor) && $config->page->editor;

    // Reset all asset(s)…
    Asset::reset();

    $__s = PANEL . DS . 'lot' . DS . 'asset' . DS . 'css' . DS;

    Asset::set([
        $__s . 'panel.min.css',
        $__editor ? $__s . 'panel.c-m.min.css' : null,
        $__s . 'panel.t-i-b.min.css',
        $__s . 'panel.t-p.min.css',
        $__s . 'panel.c-p.min.css'
    ], [
        10,
        $__editor ? 11 : null,
        12,
        13,
        14
    ]);

    $__s = PANEL . DS . 'lot' . DS . 'asset' . DS . 'js' . DS;

    Asset::set([
        $__s . 'panel.min.js',
        $__s . 'panel.fire.min.js',
        $__editor ? $__s . 'panel.c-m.min.js' : null,
        $__editor ? $__s . 'panel.c-m.fire.min.js' : null,
        $__s . 'panel.t-i-b.min.js',
        $__s . 'panel.t-i-b.fire.min.js',
        $__s . 'panel.t-p.min.js',
        $__s . 'panel.t-p.fire.min.js',
        $__s . 'panel.c-p.min.js',
        $__s . 'panel.c-p.fire.min.js'
    ], [
        10,
        10.1,
        $__editor ? 11 : null,
        $__editor ? 11.1 : null,
        12,
        12.1,
        13,
        13.1,
        14,
        14.1
    ]);

});

if ($__fn = File::exist($__path_shield . DS . 'index.php')) require $__fn;
if ($__fn = File::exist($__path_shield . DS . '__index.php')) require $__fn;

if ($__user_enter) {
    function fn_panel_asset_js_replace($__content) {
        global $language;
        $__a = array_merge([
            'languages.$' => $language->get(),
            'TIB' => Config::get('panel.f.js.TIB', o([
                'max' => 12,
                'text' => $language->TIB->text,
                'alert' => false
            ])),
            'TP' => Config::get('panel.f.js.TP', []),
            'CP' => Config::get('panel.f.js.CP', [])
        ], get_defined_constants(true)['user']);
        $__s = "";
        foreach ($__a as $__k => $__v) {
            $__s .= ';$.' . $__k . '=' . str_replace(['\\'], ['\\\\'], json_encode($__v));
        }
        return $__content . '!function($){' . substr($__s, 1) . '}(window.PANEL);';
    }
    function fn_panel_asset_replace($__content) {
        global $language;
        $__content = preg_replace(
            '#(\/panel\.min\.css(?:[?\#].+?)?"(?:\s[^<>]+?)?>)#',
            '$1<style>' . Hook::fire('panel.css', [""]) . '</style>',
        $__content);
        $__content = preg_replace(
            '#(\/panel\.fire\.min\.js(?:[?\#].+?)?"(?:\s[^<>]+?)?><\/script>)#',
            '$1<script>' . Hook::fire('panel.js', [""]) . '</script>',
        $__content);
        return $__content;
    }
    Hook::set('shield.output', 'fn_panel_asset_replace', 10);
    Hook::set('panel.js', 'fn_panel_asset_js_replace', 10);
    // Insert your panel extension’s asset(s) on panel ready event where possible
    Hook::NS('on.panel.ready', []);
}