<?php

Shield::plug('info', function($id = null) use($config, $language, $url, $__state) {
    $id = $id ?: $config->shield;
    $f = SHIELD . DS . $id . DS;
    return new Page(File::exist([
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
        'url' => $url . '/' . $__state->path . '/::g::/shield/' . $id
    ], __c2f__('Shield'));
});

Shield::plug('version', function($id, $v = null) {
    return Mecha::version($v, Shield::info($id)->version);
});