<?php

$_ = _\lot\x\panel\h\_user_action_limit_check($_);

// Prevent user(s) from modifying the `type`
if ('g' === $_['task'] && isset($_GET['type'])) {
    Alert::error(i('Permission denied for your current user status: %s', '<code>' . $user['status'] . '</code>') . '<br><small>' . $url->current . '</small>');
    Guard::kick($url->clean . $url->query('&', ['type' => false]) . $url->hash);
}
