<?php

call_user_func(function() use($config, $language) {

    // Reset all asset(s)…
    Asset::reset();

    $__s = PANEL . DS . 'lot' . DS . 'asset' . DS . 'css' . DS;

    Asset::set([
        $__s . 'panel.min.css',
        $__s . 'panel.fire.min.css'
    ], [
        10,
        10.1
    ]);

    $__s = PANEL . DS . 'lot' . DS . 'asset' . DS . 'js' . DS;

    Asset::set([
        $__s . 'panel.min.js',
        $__s . 'panel.fire.min.js'
    ], [
        10,
        10.1
    ]);

});

if ($__fn = File::exist($__path_shield . DS . 'index.php')) require $__fn;
if ($__fn = File::exist($__path_shield . DS . '__index.php')) require $__fn;

if ($__user_enter) {
    function fn_panel_asset_js_replace($__content) {
        global $language, $site;
        $__languages = [];
        foreach (glob(LANGUAGE . DS . '*.page') as $__v) {
            $__languages[basename($__v, '.page')] = 1;
        }
        $__a = array_merge([
            'language' => $site->language,
            'languages' => array_merge([
                '$' => $language->get()
            ], $__languages)
        ], get_defined_constants(true)['user'], a(Config::get('panel.c.js', [])));
        $__s = '$.url=' . json_encode(__url__()) . ';$.u_r_l=$.url;$.token="' . Guardian::token() . '"';
        foreach ($__a as $__k => $__v) {
            $__s .= ';$.' . $__k . '=' . str_replace(['\\'], ['\\\\'], json_encode($__v));
        }
        return $__content . '!function($){' . $__s . '}(window.PANEL);';
    }
    function fn_panel_asset_replace($__content) {
        global $language;
        $__content = preg_replace(
            '#(\/panel\.fire\.min\.css(?:[?\#].+?)?"(?:\s[^<>]+?)?>)#',
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