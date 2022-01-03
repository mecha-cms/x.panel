<?php namespace x\panel\task\set;

function blob($_) {
    if ('POST' !== $_SERVER['REQUEST_METHOD']) {
        return $_;
    }
    if (!empty($_['alert']['error'])) {
        return $_;
    }
    test($_POST);
    exit;
}

function data($_) {
    if ('POST' !== $_SERVER['REQUEST_METHOD']) {
        return $_;
    }
    if (!empty($_['alert']['error'])) {
        return $_;
    }
    test($_POST);
    exit;
}

function file($_) {
    if ('POST' !== $_SERVER['REQUEST_METHOD']) {
        return $_;
    }
    if (!empty($_['alert']['error'])) {
        return $_;
    }
    test($_POST);
    exit;
}

function folder($_) {
    if ('POST' !== $_SERVER['REQUEST_METHOD']) {
        return $_;
    }
    if (!empty($_['alert']['error'])) {
        return $_;
    }
    test($_POST);
    exit;
}

function page($_) {
    if ('POST' !== $_SERVER['REQUEST_METHOD']) {
        return $_;
    }
    if (!empty($_['alert']['error'])) {
        return $_;
    }
    test($_POST);
    exit;
}

function state($_) {
    if ('POST' !== $_SERVER['REQUEST_METHOD']) {
        return $_;
    }
    if (!empty($_['alert']['error'])) {
        return $_;
    }
    test($_POST);
    exit;
}