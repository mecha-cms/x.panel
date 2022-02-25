<?php

if (!array_key_exists('type', $_GET) && !isset($_['type'])) {
    if (!empty($_['part']) && $_['folder']) {
        $_['type'] = 'pages/user';
    } else if (empty($_['part']) && $_['file']) {
        $x = pathinfo($_['file'], PATHINFO_EXTENSION);
        if ('data' === $x) {
            $_['type'] = 'data';
        } else if (in_array($x, ['archive', 'draft', 'page'])) {
            $_['type'] = 'page/user';
        }
    }
}

return $_;