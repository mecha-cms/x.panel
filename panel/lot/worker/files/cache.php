<?php

Config::reset('panel.desk.header');
Config::reset('panel.+.file.tool.g');

if (glob($file . DS . '{,.}[!.,!..]*', GLOB_NOSORT | GLOB_BRACE)) {
    Config::set('panel.desk.header.tool.clear', [
        'title' => $language->do_empty_cache,
        'icon' => [['M17.65,6.35C16.2,4.9 14.21,4 12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20C15.73,20 18.84,17.45 19.73,14H17.65C16.83,16.33 14.61,18 12,18A6,6 0 0,1 6,12A6,6 0 0,1 12,6C13.66,6 15.14,6.69 16.22,7.78L13,11H20V4L17.65,6.35Z']],
        'path' => 'cache',
        'task' => '4f9d54dd',
        'stack' => 10
    ]);
}

Config::set('panel.+.file.tool.r', [
    'icon' => [['M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8.46,11.88L9.87,10.47L12,12.59L14.12,10.47L15.53,11.88L13.41,14L15.53,16.12L14.12,17.53L12,15.41L9.88,17.53L8.47,16.12L10.59,14L8.46,11.88M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z']],
    'query' => ['a' => false]
]);