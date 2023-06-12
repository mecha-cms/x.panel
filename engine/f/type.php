<?php namespace x\panel\type;

function blank(array $lot = []) {
    return \x\panel\type($lot);
}

function blob(array $lot = []) {
    return \x\panel\type($lot, 'blob');
}

function data(array $lot = []) {
    return \x\panel\type($lot, 'data');
}

function file(array $lot = []) {
    return \x\panel\type($lot, 'file');
}

function files(array $lot = []) {
    return \x\panel\type($lot, 'files');
}

function folder(array $lot = []) {
    return \x\panel\type($lot, 'folder');
}

function folders(array $lot = []) {
    return \x\panel\type\files($lot);
}

function page(array $lot = []) {
    return \x\panel\type($lot, 'page');
}

function pages(array $lot = []) {
    return \x\panel\type($lot, 'pages');
}

function state(array $lot = []) {
    return \x\panel\type($lot, 'state');
}

require __DIR__ . \D . 'type' . \D . 'blob.php';
require __DIR__ . \D . 'type' . \D . 'data.php';
require __DIR__ . \D . 'type' . \D . 'file.php';
require __DIR__ . \D . 'type' . \D . 'files.php';
require __DIR__ . \D . 'type' . \D . 'folder.php';
require __DIR__ . \D . 'type' . \D . 'folders.php';
require __DIR__ . \D . 'type' . \D . 'page.php';
require __DIR__ . \D . 'type' . \D . 'pages.php';
require __DIR__ . \D . 'type' . \D . 'state.php';