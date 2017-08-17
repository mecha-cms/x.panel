<?php

// TODO: Cache the generated file path (recursive)? :(

// Preparation(s)…
if (!Get::kin('_' . $__chops[0] . 's')) {
    Get::plug('_' . $__chops[0] . 's', function($__folder) {
        $__output = [];
        foreach (File::explore($__folder, true, true) as $__k => $__v) {
            if ($__v === 0) {
                continue;
            }
            $__x = pathinfo($__k, PATHINFO_EXTENSION);
            if (strpos(',draft,page,archive,', ',' . $__x . ',') === false) {
                continue;
            }
            $__output[basename($__k)] = $__k;
        }
        krsort($__output);
        return !empty($__output) ? $__output : false;
    });
}
Hook::set($__chops[0] . '.title', function($__title, $__lot) {
    if (!isset($__lot['path'])) {
        return $__title;
    }
    return Page::apart(file_get_contents($__lot['path']), 'author', $__title);
}, 0);
Hook::set($__chops[0] . '.description', function($__content, $__lot) {
    if (!isset($__lot['path'])) {
        return $__content;
    }
    return Page::apart(file_get_contents($__lot['path']), 'content', $__content);
}, 0);

Config::set('panel.view', 'page');