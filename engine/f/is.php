<?php namespace x\panel\is;

function dark($color) {
    if ($color = \x\panel\to\color($color)) {
        $r = \hexdec($color[1] . $color[2]);
        $g = \hexdec($color[3] . $color[4]);
        $b = \hexdec($color[5] . $color[6]);
        // Value closer to `0` will be darker, value closer to `1` will be lighter.
        // <https://en.wikipedia.org/wiki/Luma_(video)>
        return ((0.2126 * $r + 0.7152 * $g + 0.0722 * $b) / 255) < 0.5;
    }
    return null;
}

function light($color) {
    $dark = \x\panel\is\dark($color);
    if (null === $dark) {
        return $dark;
    }
    return !$dark;
}