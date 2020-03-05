<?php

// Disable the automatic syntax highlighter theme loader
State::let('x.highlight.skin');

if (isset($state->x->scss)) {
    Asset::set(__DIR__ . DS . 'asset' . DS . 'scss' . DS . 'construction.scss', 20.1);
} else {
    Asset::set(__DIR__ . DS . 'asset' . DS . 'css' . DS . 'construction' . (defined('DEBUG') && DEBUG ? '.' : '.min.') . 'css', 20.1);
}
