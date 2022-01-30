<?php

if (!empty($_['part']) && $_['folder']) {
    $_['type'] = 'pages/page';
} else if (empty($_['part']) && $_['file']) {
    if (in_array(pathinfo($_['file'], PATHINFO_EXTENSION), ['archive', 'draft', 'page'])) {
        $_['type'] = 'page/page';
    }
}

return $_;