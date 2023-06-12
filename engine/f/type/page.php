<?php namespace x\panel\type\page;

function page(array $_ = []) {
    $type = $_['type'] ?? 'page/page';
    return \x\panel\type\page(\array_replace_recursive([
        // TODO
        'type' => $type
    ], $_));
}

function user(array $lot = []) {
    $type = $_['type'] ?? 'page/user';
    return \x\panel\type\page(\array_replace_recursive([
        // TODO
        'type' => $type
    ], $_));
}