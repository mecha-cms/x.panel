<?php

$c = $panel->c;

if ($c !== 'r' && !HTTP::is('post')) {
    exit('Method not allowed.');
}

$id = $panel->id;
$r = $panel->r;
$a = HTTP::post('a');
$tab = HTTP::get('tab');
$gate_alt = File::exist(__DIR__ . DS . 'h-t-t-p' . DS . HTTP::post('view', HTTP::get('view', X)) . '.php');

$path = str_replace('/', DS, rtrim($id . '/' . $panel->path, '/'));
$directory = trim(str_replace('/', DS, HTTP::post('directory', "")), DS);
$consent = HTTP::post('consent', null, false);

// <https://stackoverflow.com/q/28672096>
if ($consent !== null) {
    $consent = octdec($consent);
}

if ($c === 'r') {
    // Prevent user(s) from deleting the root folder(s)
    if (strpos($path, DS) === false) {
        panel\message('error', 'You can\'t delete this file/folder.');
        Guardian::kick(str_replace('::r::', '::g::', $url->current . '/1'));
    }
    if ($gate_alt) {
        require $gate_alt;
    }
    $ff = is_file($f = LOT . DS . $path);
    File::open($f)->delete();
    panel\message('success', $ff ? 'File deleted.' : 'Folder deleted.');
    Guardian::kick(str_replace('::r::', '::g::', dirname($url->current)) . '/1');
}

$query = HTTP::query(['token' => false]);
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
        Guardian::kick($url->current . $query);
    }
} else if ($tab === 'blob') {
    // TODO: do file upload
} else /* if ($tab === 'file') */ {
    $name = To::file(basename(HTTP::post('name', "", false)));
    if ($c === 'g') {
        if ($a === -1) {
            if ($gate_alt) {
                require $gate_alt;
            }
            $ff = is_file($f = LOT . DS . $path);
            File::open($f)->delete();
            panel\message('success', $ff ? 'File deleted.' : 'Folder deleted.');
            HTTP::delete('post');
            Guardian::kick($r . '/::g::/' . dirname($path) . '/1');
        }
        $n = basename($path); // previous name
        $path = dirname($path);
    } else {
        $n = null;
    }
    if ($gate_alt) {
        require $gate_alt;
    }
    if ($x = HTTP::post('x', "", false)) {
        if ($name[0] === '.') {
            $name = substr($name, 1);
        }
        $name .= '.' . $x;
    }
    $content = HTTP::post('file.content', "", false);
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
        Guardian::kick($r . '/::g::/' . $path . '/' . ($directory ? str_replace(DS, '/', $directory) . '/' . $name : $name) . $query);
    } else {
        HTTP::save('post');
        Guardian::kick($url->current . $query);
    }
}