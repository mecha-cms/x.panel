<?php

if (!array_key_exists('type', $_GET) && !isset($_['type'])) {
    if (!empty($_['part']) && $_['folder']) {
        $_['type'] = 'pages/user';
    } else if (empty($_['part']) && $_['file']) {
        if ('+' === basename(dirname($_['file']))) {
            $_['type'] = 'data';
        } else if (false !== strpos(',' . x\page\x() . ',', ',' . ($x = pathinfo($_['file'], PATHINFO_EXTENSION)) . ',')) {
            if ('txt' === $x && '---' === trim(fgets(fopen($_['file'], 'r')))) {
                $_['type'] = 'page/user';
            } else {
                $_['type'] = 'page/user';
            }
        }
    }
}

return $_;