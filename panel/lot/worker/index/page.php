<?php

Hook::set('__page.url', function($content, $lot) use($__state) {
    $s = Path::F($lot['path'], LOT);
    return rtrim(__url__('url') . '/' . $__state->path . '/::g::/' . ltrim(To::url($s), '/'), '/');
});

// `panel/::s::/page` → new page in `lot\page`
// `panel/::g::/page` → index view
// `panel/::s::/page/blog` → new child page for `lot\page\blog`
// `panel/::g::/page/blog` → edit page of `lot\page\blog`
$site->is = $__is_has_step ? 'pages' : 'page';

$__is_data = substr($url->path, -2) === '/+' || strpos($url->path, '/+/') !== false;
Lot::set('__is_data', $__is_data);
if ($__f = File::exist(__DIR__ . DS . 'page' . DS . $__sgr . '.php')) require $__f;


/**
 * Field(s)
 * --------
 */

// [+] &#x2795;
// [-] &#x2796;
// [:] &#x2797;
// [x] &#x2716;