<?php

if (!array_key_exists('type', $_GET)) {
    if (!empty($_['part']) && $_['folder']) {
        $_['type'] = 'files/layout';
    }
}

return $_;