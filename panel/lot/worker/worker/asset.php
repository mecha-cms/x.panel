<?php

// Reset all asset(s)…
Asset::reset();

$s = PANEL . DS . 'lot' . DS . 'asset' . DS . 'css' . DS;

Asset::set([
    $s . 'panel.min.css',
    $s . 'panel.c-m.min.css',
    $s . 'panel.t-i-b.min.css',
    $s . 'panel.t-p.min.css',
    $s . 'panel.c-p.min.css'
], [
    10,
    11,
    12,
    13,
    14
]);

$s = PANEL . DS . 'lot' . DS . 'asset' . DS . 'js' . DS;

Asset::set([
    $s . 'panel.min.js',
    $s . 'panel.fire.min.js',
    $s . 'panel.c-m.min.js',
    $s . 'panel.c-m.fire.min.js',
    $s . 'panel.t-i-b.min.js',
    $s . 'panel.t-i-b.fire.min.js',
    $s . 'panel.t-p.min.js',
    $s . 'panel.t-p.fire.min.js',
    $s . 'panel.c-p.min.js',
    $s . 'panel.c-p.fire.min.js'
], [
    10,
    10.1,
    11,
    11.1,
    12,
    12.1,
    13,
    13.1,
    14,
    14.1
]);

if ($fn = File::exist($__path_shield . DS . 'index.php')) require $fn;
if ($fn = File::exist($__path_shield . DS . '__index.php')) require $fn;

if ($__user_enter) {
    function fn_panel_asset_js_replace($content) {
        global $language;
        $a = array_merge([
            'languages.$' => $language->get(),
            'TIB' => Config::get('panel.f.js.TIB', o([
                'max' => 12,
                'text' => $language->TIB->text,
                'alert' => false
            ])),
            'TP' => Config::get('panel.f.js.TP', []),
            'CP' => Config::get('panel.f.js.CP', [])
        ], get_defined_constants(true)['user']);
        $s = "";
        foreach ($a as $k => $v) {
            $s .= ';$.' . $k . '=' . str_replace(['\\'], ['\\\\'], json_encode($v));
        }
        return $content . '!function($){' . substr($s, 1) . '}(Panel);';
    }
    function fn_panel_asset_replace($content) {
        global $language;
        $content = preg_replace(
            '#(\/panel\.min\.css(?:[?\#].+?)?"(?:\s[^<>]+?)?>)#',
            '$1<style>' . Hook::fire('panel.css', [""]) . '</style>',
        $content);
        $content = preg_replace(
            '#(\/panel\.fire\.min\.js(?:[?\#].+?)?"(?:\s[^<>]+?)?><\/script>)#',
            '$1<script>' . Hook::fire('panel.js', [""]) . '</script>',
        $content);
        return $content;
    }
    Hook::set('shield.output', 'fn_panel_asset_replace', 10);
    Hook::set('panel.js', 'fn_panel_asset_js_replace', 10);
    // Insert your panel extension’s asset(s) on panel ready event where possible
    Hook::NS('on.panel.ready', []);
}