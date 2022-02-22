<?php

if (!array_key_exists('type', $_GET)) {
    if (!empty($_['part']) && $_['folder']) {
        $_['type'] = (false !== strpos($_['path'], '/') ? 'files' : 'pages') . '/x';
    }
}

return $_;