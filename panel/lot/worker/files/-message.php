<?php

Config::reset('panel.desk.header');
$messages = [];
foreach (glob(LOT . DS . $id . DS . '*.page', GLOB_NOSORT) as $v) {
    $v = new Page($v);
    $s = '<div class="message' . ($v->type ? ' message-' . To::slug($v->type) : "") . '">';
    $s .= '<h3 class="title">' . $v->title . '</h3>';
    $s .= '<p>' . $v->description . '</p>';
    $s .= '</div>';
    $messages['.' . $v->time] = $s;
}
krsort($messages);
Config::set('panel.desk.body.content', '<div class="messages--static">' . implode("", $messages) . '</div>');