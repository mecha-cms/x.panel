<?php

// Preparation(s)…
Hook::set('__' . $__chops[0] . '.url', function($__url) {
    return Path::D($__url);
});
Hook::set('__' . $__chops[0] . '.slug', function($__slug, $__lot) {
    return isset($__lot['path']) ? Path::B(Path::D($__lot['path'])) : null;
});
Hook::set($__chops[0] . '.url', function() {
    return false;
});
Hook::set($__chops[0] . '.title', function(...$__lot) {
    return Hook::fire('page.title', $__lot);
});
Hook::set($__chops[0] . '.description', function(...$__lot) {
    return Hook::fire('page.description', $__lot);
});
Hook::set($__chops[0] . '.content', function(...$__lot) {
    return Hook::fire('page.content', $__lot);
});
if (!Get::kin('_' . $__chops[0] . 's')) {
    Get::plug('_' . $__chops[0] . 's', function($__folder) use($config) {
        $__output = [];
        foreach (glob($__folder . DS . '*', GLOB_ONLYDIR | GLOB_NOSORT) as $__v) {
            if ($__f = File::exist([
                $__v . DS . 'about.' . $config->language . '.page',
                $__v . DS . 'about.page'
            ])) {
                $__output[] = $__f;
            } else {
                $__output[] = null;
            }
        }
        return !empty($__output) ? $__output : false;      
    });
}

// `panel/::s::/extend` → upload a new extension
// `panel/::g::/extend` → index view
// `panel/::s::/extend/page` → create a new file in `lot\extend\page`
// `panel/::g::/extend/page` → view `page` extension file(s)
Config::set('panel.v.' . $__chops[0] . '.is.pages', false);
Config::set('panel', [
    'layout' => 2,
    'c:f' => !$__is_has_step
]);
if (count($__chops) === 1) {
    if ($__command === 'g') {
        Config::set('panel.l', 'page');
        require __DIR__ . DS . '..' . DS . 'worker' . DS . 'page.php';
    } else if ($__command === 's') {
        Config::set('panel', [
            'm' => [
                't' => [
                    'file' => null,
                    'folder' => null,
                    'upload' => [
                        'legend' => $language->file,
                        'list' => [
                            'file' => [
                                'description' => $language->{'h_' . $__chops[0] . '_upload'},
                                'expand' => true,
                                'stack' => 10
                            ],
                            'extract' => [
                                'type' => 'hidden',
                                'value' => true,
                                'stack' => 20
                            ],
                            'x' => [
                                'type' => 'submit[]',
                                'value' => 'zip',
                                'text' => $language->upload,
                                'stack' => 0
                            ]
                        ]
                    ]
                ]
            ],
            's' => [
                1 => [
                    'child' => [
                        'title' => $language->{count(Config::get('panel.s.1.child.list', [[]])[0]) === 1 ? 'kin' : 'kins'}
                    ]
                ]
            ]
        ]);
    }
} else if (count($__chops) === 2) {
    $__d = LOT . DS . $__path . DS . 'about.';
    if ($__f = File::exist([
        $__d . $config->language . '.page',
        $__d . 'page'
    ])) {
        $__page = new Page($__f, [], $__chops[0]);
        $__content  = '<h2>' . $__page->title . '</h2>';
        $__content .= '<p class="h">';
        $__list = [];
        if ($__page->author) {
            $__list[] = '<strong>' . $language->author . ':</strong> ' . ($__page->link ? HTML::a($__page->author, $__page->link, true, ['rel' => 'nofollow']) : $__page->author);
        }
        if ($__page->version) {
            $__list[] = '<strong>' . $language->version . ':</strong> ' . $__page->version;
        }
        $__content .= implode('<br>', $__list) . '</p>';
        if ($__page->description) {
            $__content .= '<blockquote>' . $__page->description . '</blockquote>';
        }
        $__content .= str_replace('<!-- block:donate -->', '<hr><form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="hosted_button_id" value="TNVGH7NQ7E4EU"><p><input type="image" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFwAAAAaCAMAAAANMMsbAAABQVBMVEUAAAD/mTP/qigAM2b/tUL/wWH/7Mj/79L/8tv/zYD/9uX/2Z7/+e7+6cD/5b3+57r//fj/8dv+5rT/rzP/rC3+5K/+4KX+4ar+36H+0oT/sTj/sz0gSW/+tUJgdH5AUVb/t0i+uaCOlIpAXnUQPmtwgIW+qnx/g3X/x2wQOmKAb0fu266uq5een49/i4kgQl//vln/u06vhj3PljjvpjL/6MPOv5dQan0QPmr/w2W/jjv/4rLe0Knu1qPOwJ3+2pe+tZaeoZRAYHv+zXowVHMwUWwwSltAUVhQWVTu3rr/3KjOxajey5+epJ7/1JN/iYT+zn2OjHdgb3FAW24gR2ogQl5gYFBwaE2ff0SPdkO/kEDfnzn/3Kfu1J1/jpO+tJKuqpF/jZCelnlweXNQZW9wZ0uPeUmffT//sju/jDjfmzAzmEmSAAAAAXRSTlMAQObYZgAAAilJREFUeNq1lmlX2kAUhgmvKNpaW7KRGmNYJWHfQUAQUHC37t339f//gN4JSIFUPyXPOblz5705z5kTPgyeCZxjeObh6u/8zx1hpZHgZtWJpacO4l/mptzyisOUuYm74XecRW7sVpZcoDGy8+VFN8hbB/et2jkBoX44WH2cg5OHZ9aPKtTX7MSgdrtRVNce5Uj9/PDwXKCD/8rLARtdhAKBZhRHbBMKJcdxMtQMTCVVpKyQ3rXjW+c83EY+IdoAklRjCIvibhRAzMoy1KbGya4oskUTWag2bQYlv0FyXdd78hw7UNmSQUZOo5ZO9ZGmDP2UhojcQi0VRk0+jgGZnTD6HQ3VeUM7r+vs5IQvKM0QhsYWOrmkoiNJn1CV0lBbVGhAVABJ6iAiSTUcSy3azdJjVpKvMxYS7eAUGsJsUVGpQKXmIyKUnY0G7zUVoCB4hliwQl0kAgSnaPd0S0ryl2P0xLlyzx7iVF/hSonj0uourMwql/gW38JXRbnAF5pfbTGUCYncvZHkAm8H4PnSD+AN/xbZEl8yqAPGA/YMcMfzWRTZnOeLBf4/CCT3Cja2MeKO+gH2zSxuKNsXRiWL4RDYFgTgtiAMMbg1UBTseDkP2W0UNhnmH9aXvhu4Nln2elx+XxumuVn0ek0DP72lGwO0sUNuJncJkpN9wRXIbcl9LkDykT237Dg57t/t/MRh6tOXqH74zEEO9bn7P3dafuEI5dPJJ3H1T9Ff6nuMWGU5acwAAAAASUVORK5CYII=" name="submit" alt="PayPal &ndash; The safer, easier way to pay online!" title="Using an open source project is incredibly fun and cheap, but we also need costs to maintain and keep them exist in the `www`."><img alt="" src="https://www.paypalobjects.com/id_ID/i/scr/pixel.gif" width="1" height="1"></p></form>', $__page->content);
        Config::set('panel', [
            'c:f' => false,
            'm:f' => false,
            'm' => [
                't' => [
                    'info' => [
                        'title' => $language->info,
                        'content' => $__content,
                        'stack' => 10
                    ],
                    'file' => null
                ]
            ]
        ]);
    }
}

if (count($__chops) === 1) {
    Hook::set('panel.a.' . $__chops[0], function($__a, $__v) use($language, $__chops) {
        if (file_exists(LOT . DS . $__chops[0] . DS . $__v[0]->slug . DS . 'lot' . DS . 'state' . DS . 'config.php')) {
            $__a = ['state' => [$language->setting, $__a['edit'][1] . '/lot/state/config.php' . HTTP::query(['token' => false, 'force' => false])]] + $__a;
        }
        $__a['edit'][0] = $language->open;
        return $__a;
    });
}