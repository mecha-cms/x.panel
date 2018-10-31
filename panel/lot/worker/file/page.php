<?php

// Force `view` value to `page`
$panel->view = $panel->v = HTTP::get('view', 'page');
require __DIR__ . DS . '..' . DS . 'page.php';