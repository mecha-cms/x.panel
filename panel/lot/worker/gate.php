<?php

if (!HTTP::is('post')) {
    exit('Method not allowed.');
}

$c = $panel->c;
$a = HTTP::post('a');
$tab = HTTP::get('tab');

if ($tab === 'folder') {
  
} else /* if ($tab === 'file') */ {
    $path = str_replace('/', DS, rtrim($panel->id . '/' . $panel->path, '/'));
    $directory = trim(str_replace('/', DS, HTTP::post('directory', "")), DS);
    $name = basename(HTTP::post('name'));
    if ($c === 'g') {
        if ($a === -1) {
            File::open(LOT . DS . $path)->delete();
            panel\message('success', 'File deleted.');
            Guardian::kick($panel->r . '/::g::/' . dirname($path) . '/1');
        }
        $n = basename($path); // previous name
        $path = dirname($path);
    } else {
        $n = null;
    }
    $content = HTTP::post('file.content', "");
    $consent = HTTP::post('consent');
    if (Is::void($content)) {
        panel\message('error', 'Please fill out the content field!');
    }
    if (Is::void($name)) {
        panel\message('error', 'Please fill out the name field!');
    }
    if (!Message::$x) {
        File::set($content)->saveTo(LOT . DS . $path . DS . ($directory ? $directory . DS . $name : $name), $consent);
        Session::set('panel.file.active', $name);
        if ($n && ($directory || $n !== $name)) {
            File::open(LOT . DS . $path . DS . $n)->delete();
        }
        panel\message('success', $c === 's' ? 'File created.' : 'File updated.');
        Guardian::kick($panel->r . '/::g::/' . $path . '/' . ($directory ? str_replace(DS, '/', $directory) . '/' . $name : $name));
    } else {
        HTTP::save('post');
        Guardian::kick($url->current . HTTP::query(['token' => false]));
    }
}

/*
$name = basename(HTTP::post('name', ""));
$directory = ;
$path = str_replace('/', DS, $p = rtrim($panel->id . '/' . $panel->path, '/'));
if ($c === 'g') {
    $file = LOT . DS . rtrim($path);
    if ($directory) {
        $p .= '/' . str_replace(DS, '/', $directory);
    }
} else {
    $file = LOT . DS . rtrim($path . DS . $directory, DS) . DS . $name;
    $p .= '/' . str_replace(DS, '/', $directory);
}
$consent = HTTP::post('consent', 0600);

$tab = HTTP::get('tab');
if ($tab === 'file' && trim($name) === "") {
    panel\message('error', 'Please fill out the name field.');
} else if ($tab === 'folder' && trim($directory) === "") {
    panel\message('error', 'Please fill out the directory field.');
}

// Prevent directory traversal attack <https://en.wikipedia.org/wiki/Directory_traversal_attack>
$file = str_replace('..' . DS, "", urldecode($file));

if ($c === 'g' && $a === -1) {
    File::open($file)->delete();
    panel\message('success', 'File deleted.');
    Guardian::kick($panel->r . '/::g::/' . dirname($p) . '/1');
} else if (!Message::$x) {
    if ($content = HTTP::post('file.content')) {
        File::set($content)->saveTo($file, $consent);
        panel\message('success', $c === 'g' ? 'File updated.' : 'File created.');
    } else {
        if ($c === 's') {
            Folder::set($file, $consent);
            panel\message('success', 'Folder created.');
        } else {
            File::open($file)->moveTo(rtrim(dirname($file) . DS . ($directory ? $directory . DS . $name : $name), DS));
            panel\message('success', 'Folder moved.');
        }
    }
    if ($c === 'g') {
        $_file = str_replace('/', DS, HTTP::post('trace'));
        if ($_file && $file !== ($_file = LOT . DS . $_file)) {
            File::open($_file)->delete(); // Remove old file location
        }
        Guardian::kick($panel->r . '/::g::/' . rtrim(dirname($p) . '/' . $name, '/'));
    } else {
        Guardian::kick($panel->r . '/::g::/' . rtrim($p, '/') . '/' . $name);
    }
} else {
    Guardian::kick($url->current . HTTP::query(['token' => false]));
}
*/