<?php

require __DIR__ . DS . '..' . DS . 'data.php';

Config::set('panel.desk.body.tab.file.field', [
    'name' => ['pattern' => '^-?[a-z\\d]+([-.][a-z\\d]+)*$'] // Allow dot(s)
]);