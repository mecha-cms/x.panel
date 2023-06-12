<?php namespace x\panel\type;

function blank($_) {}
function blob($_) {}
function data($_) {}
function file($_) {}
function files($_) {}
function folder($_) {}
function folders($_) {}
function page($_) {}
function pages($_) {}
function state($_) {}

require __DIR__ . \D . 'type' . \D . 'blob.php';
require __DIR__ . \D . 'type' . \D . 'data.php';
require __DIR__ . \D . 'type' . \D . 'file.php';
require __DIR__ . \D . 'type' . \D . 'files.php';
require __DIR__ . \D . 'type' . \D . 'folder.php';
require __DIR__ . \D . 'type' . \D . 'folders.php';
require __DIR__ . \D . 'type' . \D . 'page.php';
require __DIR__ . \D . 'type' . \D . 'pages.php';
require __DIR__ . \D . 'type' . \D . 'state.php';