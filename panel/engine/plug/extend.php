<?php

Extend::plug('info', function($id) use($config, $language, $url, $__state) {
    $f = EXTEND . DS . $id . DS;
    return Page::_(File::exist([
        // Check whether the localized “about” file is available
        $f . 'about.' . $config->language . '.page',
        // Use the default “about” file if available
        $f . 'about.page'
    ], null), [
        'id' => Folder::exist($f) ? $id : null,
        'title' => To::title($id),
        'author' => $language->anonymous,
        'version' => '0.0.0',
        'content' => $language->_message_avail($language->description),
        'url' => $url . '/' . $__state->path . '/::g::/extend/' . $id
    ], __c2f__('Extend'));
});

Extend::plug('version', function($id, $v = null) {
    return Mecha::version($v, Extend::info($id)->version);
});