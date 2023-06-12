<?php namespace x\panel\type\pages;

function page(array $lot = []) {
    return \x\panel\type($lot, 'pages/page');
}

function user(array $lot = []) {
    return \x\panel\type($lot, 'pages/user');
}

function x(array $lot = []) {
    return \x\panel\type($lot, 'pages/x');
}

function y(array $lot = []) {
    return \x\panel\type($lot, 'pages/y');
}