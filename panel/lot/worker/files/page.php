<?php

// Force `view` value to `page`
$panel->v = ($panel->view = 'page') . 's';
require __DIR__ . DS . '..' . DS . 'pages.php';