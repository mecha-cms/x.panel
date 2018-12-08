<?php

// Force `view` value to `data`
require __DIR__ . DS . '..' . DS . ($panel->v = $panel->view = 'data') . DS . 'block.php';