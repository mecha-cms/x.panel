<?php

$site->is = 'pages';

foreach (glob($__folder . DS . '*') as $_v) {
    $_s = File::inspect($_v);
    $__pages[0][] = new Page(null, $_s, '__asset');
    $__pages[1][] = new Page(null, $_s, 'asset');
}
Lot::set('__pages', $__pages);