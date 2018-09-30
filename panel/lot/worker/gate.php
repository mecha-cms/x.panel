<?php

$c = $panel->c;

if ($c !== 'r' && !HTTP::is('post')) {
    exit('Method not allowed.');
}

$a = HTTP::post('a');
$tab = HTTP::get('tab');

$path = str_replace('/', DS, rtrim($panel->id . '/' . $panel->path, '/'));
$directory = trim(str_replace('/', DS, HTTP::post('directory', "")), DS);
$consent = HTTP::post('consent');

if ($c === 'r') {
    // Prevent user(s) from deleting the root folder(s)
    if (strpos($path, DS) === false) {
        panel\message('error', 'You can\'t delete this file/folder.');
        Guardian::kick(str_replace('::r::', '::g::', $url->current . '/1'));
    }
    $ff = is_file($f = LOT . DS . $path);
    File::open($f)->delete();
    panel\message('success', $ff ? 'File deleted.' : 'Folder deleted.');
    Guardian::kick(str_replace('::r::', '::g::', dirname($url->current)) . '/1');
}

if ($tab === 'folder') {
    if (Is::void($directory)) {
        panel\message('error', 'Please fill out the directory field!');
    }
    if (!Message::$x) {
        Folder::set(LOT . DS . $path . DS . $directory, $consent);
        Session::set('panel.file.active', LOT . DS . $path . DS . explode(DS, $directory)[0]);
        panel\message('success', 'Folder created.');
        HTTP::delete('post');
        Guardian::kick(str_replace('::s::', '::g::', $url->current) . '/1');
    } else {
        HTTP::save('post');
        Guardian::kick($url->current . HTTP::query(['token' => false]));
    }
} else if ($tab === 'blob') {
    
} else /* if ($tab === 'file') */ {
    $name = basename(HTTP::post('name'));
    if ($c === 'g') {
        if ($a === -1) {
            $ff = is_file($f = LOT . DS . $path);
            File::open($f)->delete();
            panel\message('success', $ff ? 'File deleted.' : 'Folder deleted.');
            HTTP::delete('post');
            Guardian::kick($panel->r . '/::g::/' . dirname($path) . '/1');
        }
        $n = basename($path); // previous name
        $path = dirname($path);
    } else {
        $n = null;
    }
    $content = HTTP::post('file.content', "");
    if (Is::void($content)) {
        panel\message('error', 'Please fill out the content field!');
    }
    if (Is::void($name)) {
        panel\message('error', 'Please fill out the name field!');
    }
    if (!Message::$x) {
        File::set($content)->saveTo($f = LOT . DS . $path . DS . ($directory ? $directory . DS . $name : $name), $consent);
        Session::set('panel.file.active', $f);
        if ($n && ($directory || $n !== $name)) {
            File::open(LOT . DS . $path . DS . $n)->delete();
        }
        panel\message('success', $c === 's' ? 'File created.' : 'File updated.');
        HTTP::delete('post');
        Guardian::kick($panel->r . '/::g::/' . $path . '/' . ($directory ? str_replace(DS, '/', $directory) . '/' . $name : $name));
    } else {
        HTTP::save('post');
        Guardian::kick($url->current . HTTP::query(['token' => false]));
    }
}