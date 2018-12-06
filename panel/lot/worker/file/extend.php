<?php

if ($c === 's' && HTTP::get('tab.0') === 'blob' && (HTTP::is('get', 'tabs.0') && !HTTP::get('tabs.0'))) {
    if (!$path || $path === 'plugin/lot/worker') {
        if (Extend::exist('package')) {
            Config::reset('panel.desk.body.tab.blob.field.package');
            Config::set('panel.desk.body.tab.blob.field.hints', [
                'key' => 'hints',
                'title' => false,
                'type' => 'content',
                'value' => $language->field_description_package__($path ? 'plugin' : $id),
                'stack' => 10.1
            ]);
            // Force extract
            Config::set('panel.desk.body.tab.blob.field.package[extract]', [
                'key' => 'extract',
                'type' => 'hidden',
                'value' => 1,
                'stack' => 0
            ]);
        }
    }
}

if (strpos($path, $chops[0] . '/lot/state/') === 0) {
    require __DIR__ . DS . 'state.php';
}