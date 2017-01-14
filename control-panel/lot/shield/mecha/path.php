<?php

$path = $url->path;
$format = ['<span>%{0}%</span>', '<a href="%{1}%">%{0}%</a>'];

$a = explode('/', $path);
$b = __replace__($format[!$path || $path === $config->slug ? 0 : 1], [$language->home, $url]);
$c = "";

while ($d = array_shift($a)) {
    $c .= '/' . $d;
    $d = is_numeric($d) ? __replace__($format[0], $language->page . ' ' . $d) : To::title($d);
    $d = Page::open(PAGE . DS . $c . '.' . $config->state)->get('title', $d);
    $b .= ' / ' . __replace__($format['/' . $path === $c ? 0 : 1], [$d, $url . $c]);
}

echo $b;