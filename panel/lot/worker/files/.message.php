<?php

Config::reset('panel.desk.header');
$messages = [];
foreach (glob($file . DS . '*.page', GLOB_NOSORT) as $v) {
    $v = new Page($v);
    $title = $v->title;
    $description = $v->description;
    $link = $v->link;
    $s = '<div class="message' . ($v->type ? ' message-' . To::slug($v->type) : "") . '">';
    $s .= $title ? '<h3 class="title">' . $title . '</h3>' : "";
    $s .= $description ? '<p>' . $description . '</p>' : "";
    $s .= fn\panel\links([
        'enter' => [
            'url' => $link,
            'stack' => 10
        ],
        'r' => [
            'title' => $language->ignore,
            'c' => 'r',
            'path' => Path::R($v->path, LOT, '/'),
            'query' => [
                'a' => false,
                'token' => $token
            ],
            'stack' => 10.1
        ]
    ]);
    $s .= '</div>';
    $messages['.' . $v->time] = $s;
}
krsort($messages);
Config::set('panel.desk.body.content', '<div class="messages--static">' . implode("", $messages) . '</div>');