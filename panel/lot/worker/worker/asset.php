<?php

call_user_func(function() use($config, $language) {

    // Reset all asset(s)…
    Asset::reset();

    $__s = PANEL . DS . 'lot' . DS . 'asset' . DS . 'css' . DS;

    Asset::set([
        $__s . 'panel.min.css',
        $__s . 'panel.fire.min.css'
    ], [
        9,
        9.1
    ]);

    $__s = PANEL . DS . 'lot' . DS . 'asset' . DS . 'js' . DS;

    Asset::set([
        $__s . 'panel.min.js',
        $__s . 'panel.fire.min.js'
    ], [
        9,
        9.1
    ]);

});

if ($__fn = File::exist($__path_shield . DS . 'index.php')) require $__fn;
if ($__fn = File::exist($__path_shield . DS . '__index.php')) require $__fn;

if ($__user_enter) {
    function fn_panel_asset_js_replace($__content) {
        global $language, $site;
        $__language = $site->language;
        $__languages = [];
        $__constants = get_defined_constants(true)['user'];
        unset($__constants['PANEL']); // Remove `PANEL` constant!
        foreach (glob(LANGUAGE . DS . '*.page', GLOB_NOSORT) as $__v) {
            $__v = basename($__v, '.page');
            $__languages[$__v] = $__v === $__language ? true : false;
        }
        $__a = array_merge([
            '$language' => $language->get(),
            'languages' => $__languages,
            '$config' => (array) a(Config::get('panel.o.js', [])),
        ], $__constants);
        $__s = '$.$url=' . json_encode($GLOBALS['URL']) . ';$.$u_r_l=$.$url;$.$token="' . Guardian::token() . '"';
        foreach ($__a as $__k => $__v) {
            $__s .= ';$.' . $__k . '=' . str_replace(['\\'], ['\\\\'], json_encode($__v));
        }
        return $__content . '!function($){' . $__s . '}(window);';
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
    Hook::fire('on.panel.ready', []);
}