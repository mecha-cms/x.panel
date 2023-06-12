<?php namespace x\panel\type\page;

function page(array $lot = []) {
    return \x\panel\type($lot, 'page/page');
}

function user(array $lot = []) {
    return \x\panel\type($lot, 'page/user');
}