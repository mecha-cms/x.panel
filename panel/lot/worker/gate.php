<?php

if (!HTTP::is('post')) {
    exit('Method not allowed.');
}

$c = $panel->c;
$a = HTTP::post('a');
$name = basename(HTTP::post('name', ""));
$directory = str_replace('/', DS, HTTP::post('directory', ""));
$path = str_replace('/', DS, HTTP::post('path', ""));
$file = LOT . DS . rtrim($path . DS . $directory, DS) . DS . $name;
$consent = HTTP::post('consent', 0600);

if (trim($name) === "") {
    panel\message('error', 'Please fill out the name field.');
}

// Prevent directory traversal attack <https://en.wikipedia.org/wiki/Directory_traversal_attack>
$file = str_replace('..' . DS, "", urldecode($file));

if ($c === 'g' && $a === -1) {
    File::open($file)->delete();
    panel\message('success', 'File deleted.');
    Guardian::kick($panel->r . '/::g::/' . rtrim(str_replace(DS, '/', $path)));
} else if (!Message::$x) {
    if ($content = HTTP::post('file.content')) {
        File::set($content)->saveTo($file, $consent);
    }
    $_file = HTTP::post('_file');
    if ($_file && $file !== $_file) {
        File::open($_file)->delete();
    }
    Guardian::kick($panel->r . '/::g::/' . str_replace(DS, '/', $path) . '/' . $name);
} else {
    Guardian::kick($panel->r . '/::' . $c . '::/' . rtrim(str_replace(DS, '/', $path) . '/' . $name, '/'));
}