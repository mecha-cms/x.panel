<?php

// Force `view` value to `page`
require __DIR__ . DS . '..' . DS . ($panel->v = ($panel->view = 'page') . 's') . DS . 'tag.php';