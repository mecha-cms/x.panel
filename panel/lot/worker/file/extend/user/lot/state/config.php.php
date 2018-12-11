<?php

Config::set('panel.desk.body.tab.file.field', [
    'file[?][_path]' => [
        'title' => $language->log_in,
        'description' => $language->state_user_description_path,
        'pattern' => '^[_.-]?[a-z\\d]+(-[a-z\\d]+)*([\\\/][_.-]?[a-z\\d]+(-[a-z\\d]+)*)*$'
    ],
    'file[?][try]' => [
        'title' => $language->attempt,
        'description' => $language->state_user_description_try
    ],
    'file[?][user]' => ['type' => 'hidden']
]);