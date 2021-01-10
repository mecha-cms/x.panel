<?php

// Tell other(s) that this layout is using a dark color scheme!
State::set('is.dark', true);

Asset::set(__DIR__ . DS . 'asset' . DS . 'css' . DS . 'index' . (defined('DEBUG') && DEBUG ? '.' : '.min.') . 'css', 20.1);
