<?php namespace x\panel\type\pages;

function page(array $_ = []) {
    $type = $_['type'] ?? 'pages/page';
    return \x\panel\type\page(\array_replace_recursive([
        // TODO
        'type' => $type
    ], $_));
}

function user(array $_ = []) {
    $type = $_['type'] ?? 'pages/user';
    return \x\panel\type\page(\array_replace_recursive([
        // TODO
        'type' => $type
    ], $_));
}

function x(array $_ = []) {
    $type = $_['type'] ?? 'pages/x';
    return \x\panel\type\page(\array_replace_recursive([
        // TODO
        'type' => $type
    ], $_));
}

function y(array $_ = []) {
    $type = $_['type'] ?? 'pages/y';
    return \x\panel\type\page(\array_replace_recursive([
        // TODO
        'type' => $type
    ], $_));
}