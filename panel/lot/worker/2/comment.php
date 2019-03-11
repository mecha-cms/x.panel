<?php

Config::set('panel.+.page.tool.g.if', $if = function($file): array {
    if (is_dir($file)) {
        $file = File::exist([
            $file . '.draft',
            $file . '.page',
            $file . '.archive',
        ]);
    }
    return ['x' => !Is::user((new Page($file, [], false))->author)];
});

Config::set('panel.+.file.tool.g.if', $if);

Hook::set('start', function() use($c, $file, $panel) {
    if ((HTTP::get('view') ?? $panel->view) === 'data') {
        $file = dirname($file);
    }
    if (is_dir($file)) {
        $file = File::exist([
            $file . '.draft',
            $file . '.page',
            $file . '.archive'
        ]);
    }
    if ($c === 'g' && Config::get('panel.+.form.editor') && !Is::user((new Page($file))['author'])) {
        Config::set('panel.error', true);
    }
}, 10);