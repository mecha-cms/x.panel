<?php

$_ = _\lot\x\panel\_error_user_check();

// Prevent user(s) from modifying the `type`
// TODO: Move this to a configurable state.
if ('g' === $_['task'] && isset($_GET['type'])) {
    Alert::error(i('Permission denied.') . '<br><small>' . $url->current . '</small>');
    Guard::kick($url->clean . $url->query('&', ['type' => false]) . $url->hash);
}
