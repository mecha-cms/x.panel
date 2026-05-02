<?php

if (!array_key_exists('type', $_GET) && !isset($_['type'])) {
    if (!empty($_['part']) && $_['folder']) {
        if ('+' !== basename($_['folder'])) {
            $_['type'] = 'pages/page';
        }
    } else if (empty($_['part']) && $_['file']) {
        if (false !== strpos(',' . x\page\x() . ',', ',' . ($x = pathinfo($_['file'], PATHINFO_EXTENSION)) . ',')) {
            if ('txt' === $x && '---' === trim(fgets(fopen($_['file'], 'r')))) {
                $_['type'] = 'page/page';
            } else if ('+' === basename(dirname($_['file']))) {
                $_['type'] = 'data';
            } else {
                $_['type'] = 'page/page';
            }
        }
    }
}

return $_;