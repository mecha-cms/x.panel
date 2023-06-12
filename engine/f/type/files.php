<?php namespace x\panel\type\files;

function cache(array $lot = []) {
    return \x\panel\type($lot, 'files/cache');
}

function trash(array $lot = []) {
    return \x\panel\type($lot, 'files/trash');
}

function x(array $lot = []) {
    return \x\panel\type($lot, 'files/x');
}

function y(array $lot = []) {
    return \x\panel\type($lot, 'files/y');
}