<?php

$headers = [
    'title' => function($s) {
        return w($s, HTML_WISE_I) ?: false;
    },
    'description' => function($s) {
        return w($s, HTML_WISE_I . ',p') ?: false;
    },
    'type' => 'HTML',
    'link' => false,
    'author' => function($s) {
        return w($s) ?: false;
    },
    'content' => ""
];

$o = (array) Config::get($id, [], true);
if (count($o) === 1 && isset($o[0])) {
    $o = false; // numeric array, not a page configuration file
}
foreach ($headers as $k => $v) {
    if (!isset($page[$k])) continue;
    if (is_callable($v)) {
        $headers[$k] = call_user_func($v, $page[$k]);
    } else if ($o !== false) {
        $headers[$k] = isset($o[$k]) ? false : $page[$k];
    } else {
        $headers[$k] = $page[$k];
    }
    unset($page[$k]);
}
$headers = array_replace_recursive($headers, $page);
$slug = To::slug(HTTP::post('slug', "", false)) ?: date('Y-m-d-H-i-s');

Set::post('name', $slug . '.' . HTTP::post('x', 'draft', false));
Set::post('file.content', Page::unite($headers));