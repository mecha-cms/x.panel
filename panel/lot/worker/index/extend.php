<?php

// Preparation(s)…
if ($__command !== 'r') {
    Hook::set('__' . $__chops[0] . '.url', function($__url) {
        return Path::D($__url);
    }, 0);
}
Hook::set($__chops[0] . '.image', function($__image, $__lot) {
    $__r = dirname($__lot['path']);
    $__d = $__r . DS . 'lot' . DS . 'asset' . DS;
    $__n = DS . basename($__r) . '.';
    return To::url(File::exist([
        $__d . 'gif' . $__n . 'gif',
        $__d . 'jpg' . $__n . 'jpg',
        $__d . 'png' . $__n . 'png'
    ], $__image));
}, 0);
Hook::set('__' . $__chops[0] . '.slug', function($__slug, $__lot) {
    return isset($__lot['path']) ? Path::B(Path::D($__lot['path'])) : null;
}, 0);
Hook::set($__chops[0] . '.url', function() {
    return false;
}, 0);
Hook::set($__chops[0] . '.title', function(...$__lot) {
    return Hook::fire('page.title', $__lot);
}, 0);
Hook::set($__chops[0] . '.description', function(...$__lot) {
    return Hook::fire('page.description', $__lot);
}, 0);
Hook::set($__chops[0] . '.content', function(...$__lot) use($config, $url, $__chops, $__state) {
    if (!empty($__lot[1]['dependency'])) {
        $__dependency = $__lot[1]['dependency'];
        $__lot[0] .= N . N . '---' . N . N . '### Dependency';
        if (!empty($__dependency['extension'])) {
            $__lot[0] .= N . N . '#### Extension' . N;
            foreach ($__dependency['extension'] as $__v) {
                if ($__f = File::exist([
                    EXTEND . DS . $__v . DS . 'about.' . $config->language . '.page',
                    EXTEND . DS . $__v . DS . 'about.page'
                ])) {
                    $__page = new Page($__f, [], $__chops[0]);
                    $__lot[0] .= N . ' - [' . $__page->title . '](' . $url . '/' . $__state->path . '/::g::/extend/' . $__v . ')';
                } else {
                    $__lot[0] .= N . ' - <s style="color:red;">' . $__v . '</s>';
                }
            }
        }
        if (!empty($__dependency['plugin'])) {
            $__lot[0] .= N . N . '#### Plugin' . N;
            foreach ($__dependency['plugin'] as $__v) {
                if ($__f = File::exist([
                    PLUGIN . DS . $__v . DS . 'about.' . $config->language . '.page',
                    PLUGIN . DS . $__v . DS . 'about.page'
                ])) {
                    $__page = new Page($__f, [], $__chops[0]);
                    $__lot[0] .= N . ' - [' . $__page->title . '](' . $url . '/' . $__state->path . '/::g::/extend/plugin/lot/worker/' . $__v . '/1)';
                } else {
                    $__lot[0] .= N . ' - <s style="color:red;">' . $__v . '</s>';
                }
            }
        }
    }
    return Hook::fire('page.content', $__lot);
}, 0);
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
        natsort($__output);
        return !empty($__output) ? $__output : false;      
    });
}

// `panel/::s::/extend` → upload a new extension
// `panel/::g::/extend` → index view
// `panel/::s::/extend/page` → create a new file in `lot\extend\page`
// `panel/::g::/extend/page` → view `page` extension file(s)
$__query = HTTP::query([
    'token' => false,
    'r' => false
]);
Config::set('panel.v.' . $__chops[0] . '.is.pages', false);
Config::set('panel', [
    'layout' => 2,
    'c:f' => !$__is_has_step
]);
Hook::set('shield.enter', function() {
    extract(Lot::get(null, []));
    if ($__command === 's' && count($__chops) === 1) {
        if (!empty($__childs[0])) {
            foreach ($__childs[1] as $__k => $__v) {
                $__d = LOT . DS . $__chops[0] . DS . $__childs[0][$__k]->name . DS . 'about.';
                if ($__f = File::exist([
                    $__d . $config->language . '.page',
                    $__d . 'page'
                ])) {
                    $__v->title = (new Page($__f, [], $__chops[0]))->title;
                }
            }
            Lot::set('__childs', $__childs);
            Config::set('panel.s.1.child.list', $__childs);
        }
    } else if (count($__chops) === 2) {
        if (!empty($__kins[0])) {
            foreach ($__kins[1] as $__k => $__v) {
                $__d = LOT . DS . $__chops[0] . DS . $__kins[0][$__k]->name . DS . 'about.';
                if ($__f = File::exist([
                    $__d . $config->language . '.page',
                    $__d . 'page'
                ])) {
                    $__v->title = (new Page($__f, [], $__chops[0]))->title;
                }
            }
            Lot::set('__kins', $__kins);
            Config::set('panel.s.1.kin.list', $__kins);
        }
    }
}, 0);
if (count($__chops) === 1) {
    if ($__command === 'g') {
        Config::set('panel.l', 'page');
        Config::set('panel.v.' . $__chops[0] . '.is.pages', 'plugin');
        Hook::set('panel.a.' . $__chops[0], function($__a, $__v) use($language, $__chops, $__query) {
            if (file_exists(LOT . DS . $__chops[0] . DS . $__v[0]->slug . DS . 'lot' . DS . 'state' . DS . 'config.php')) {
                $__a = ['state' => [$language->setting, $__a['edit'][1] . '/lot/state/config.php' . $__query]] + $__a;
            }
            $__a['edit'][0] = $language->info;
            if ($__v[0]->slug === 'plugin') {
                $__a['get'] = [$language->explore, $__a['edit'][1] . '/lot/worker/1' . $__query];
            } else if ($__v[0]->slug === 'panel') {
                unset($__a['reset']);
            }
            return $__a;
        }, 0);
    } else if ($__command === 's') {
        Config::set('panel', [
            'm' => [
                't' => [
                    'file' => false,
                    'folder' => false,
                    'upload' => [
                        'legend' => $language->file,
                        'list' => [
                            'file' => [
                                'description' => $language->{'h_' . $__chops[0] . '_upload'},
                                'expand' => true,
                                'stack' => 10
                            ],
                            'o[upload]' => [
                                'type' => 'hidden',
                                'value' => [
                                    'extract' => 1,
                                    'exist_reset' => 1
                                ],
                                'stack' => 20
                            ],
                            'x' => [
                                'key' => 'submit',
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
        Hook::set('on.package.set', function(...$__lot) use($config, $language, $__chops, $__state, $__query) {
            $__d = $__lot[0];
            if ($__f = File::exist([
                $__d . DS . 'about.' . $config->language . '.page',
                $__d . DS . 'about.page'
            ])) {
                Hook::fire('on.' . $__chops[0] . '.set', $__lot);
                Message::reset();
                Message::success('set', [Config::get('panel.n.' . $__chops[0] . '.text', $language->{$__chops[0]}), '<strong>' . (new Page($__f, [], $__chops[0]))->title . '</strong>']);
                Guardian::kick($__state->path . '/::g::/' . $__chops[0] . '/' . basename($__d) . $__query);
            }
        });
    }
} else if ($__command === 'g' && count($__chops) === 2) {
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
        $__content .= str_replace('<!-- block:donate -->', '<form class="form-donate" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="hosted_button_id" value="TNVGH7NQ7E4EU"><p><input type="image" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFwAAAAaCAMAAAANMMsbAAABQVBMVEUAAAD/mTP/qigAM2b/tUL/wWH/7Mj/79L/8tv/zYD/9uX/2Z7/+e7+6cD/5b3+57r//fj/8dv+5rT/rzP/rC3+5K/+4KX+4ar+36H+0oT/sTj/sz0gSW/+tUJgdH5AUVb/t0i+uaCOlIpAXnUQPmtwgIW+qnx/g3X/x2wQOmKAb0fu266uq5een49/i4kgQl//vln/u06vhj3PljjvpjL/6MPOv5dQan0QPmr/w2W/jjv/4rLe0Knu1qPOwJ3+2pe+tZaeoZRAYHv+zXowVHMwUWwwSltAUVhQWVTu3rr/3KjOxajey5+epJ7/1JN/iYT+zn2OjHdgb3FAW24gR2ogQl5gYFBwaE2ff0SPdkO/kEDfnzn/3Kfu1J1/jpO+tJKuqpF/jZCelnlweXNQZW9wZ0uPeUmffT//sju/jDjfmzAzmEmSAAAAAXRSTlMAQObYZgAAAilJREFUeNq1lmlX2kAUhgmvKNpaW7KRGmNYJWHfQUAQUHC37t339f//gN4JSIFUPyXPOblz5705z5kTPgyeCZxjeObh6u/8zx1hpZHgZtWJpacO4l/mptzyisOUuYm74XecRW7sVpZcoDGy8+VFN8hbB/et2jkBoX44WH2cg5OHZ9aPKtTX7MSgdrtRVNce5Uj9/PDwXKCD/8rLARtdhAKBZhRHbBMKJcdxMtQMTCVVpKyQ3rXjW+c83EY+IdoAklRjCIvibhRAzMoy1KbGya4oskUTWag2bQYlv0FyXdd78hw7UNmSQUZOo5ZO9ZGmDP2UhojcQi0VRk0+jgGZnTD6HQ3VeUM7r+vs5IQvKM0QhsYWOrmkoiNJn1CV0lBbVGhAVABJ6iAiSTUcSy3azdJjVpKvMxYS7eAUGsJsUVGpQKXmIyKUnY0G7zUVoCB4hliwQl0kAgSnaPd0S0ryl2P0xLlyzx7iVF/hSonj0uourMwql/gW38JXRbnAF5pfbTGUCYncvZHkAm8H4PnSD+AN/xbZEl8yqAPGA/YMcMfzWRTZnOeLBf4/CCT3Cja2MeKO+gH2zSxuKNsXRiWL4RDYFgTgtiAMMbg1UBTseDkP2W0UNhnmH9aXvhu4Nln2elx+XxumuVn0ek0DP72lGwO0sUNuJncJkpN9wRXIbcl9LkDykT237Dg57t/t/MRh6tOXqH74zEEO9bn7P3dafuEI5dPJJ3H1T9Ff6nuMWGU5acwAAAAASUVORK5CYII=" name="submit" alt="PayPal &ndash; The safer, easier way to pay online!" title="Using an open source project is incredibly fun and cheap, but we also need costs to maintain and keep them exist in the `www`."><img alt="" src="https://www.paypalobjects.com/id_ID/i/scr/pixel.gif" width="1" height="1"></p></form>', $__page->content);
        $__loc = '://localhost';
        $__host = '://' . $url->host;
        $__content = str_replace([
            $__loc . '`',
            $__loc . "'",
            $__loc . '"',
            $__loc . '/',
            $__loc . '#',
            $__loc . '?',
            $__loc . '&'
        ], [
            $__host . '`',
            $__host . "'",
            $__host . '"',
            $__host . '/',
            $__host . '#',
            $__host . '?',
            $__host . '&'
        ], $__content);
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
                    'file' => false
                ]
            ],
            's' => [
                1 => [
                    'kin' => [
                        'list' => $__kins
                    ],
                    'child' => false
                ]
            ]
        ]);
        Hook::set('panel.a.' . $__chops[0] . 's', function($__a) use($language, $url, $__is_has_step) {
            if ($__is_has_step) {
                $__a = ['reset' => [$language->cancel, URL::I($url->current)]] + $__a;
            } else {
                $__a = ['get' => [$language->explore, $url->current . '/1']] + $__a;
            }
            unset($__a['reset']);
            return $__a;
        }, 0);
    }
} else if ($__command === 'r' && count($__chops) === 2) {
    // Disallow user to delete the `panel` extension this way!
    if ($__chops[1] === 'image') {
        Shield::abort(PANEL_ERROR, [409]); // `Conflict`
    }
    $__d = LOT . DS . $__path;
    if (!Message::$x && $__f = File::exist([
        $__d . DS . 'about.' . $config->language . '.page',
        $__d . DS . 'about.page'
    ])) {
        Message::success('reset', [Config::get('panel.n.' . $__chops[0] . '.text', $language->{$__chops[0]}), '<strong>' . (new Page($__f, [], $__chops[0]))->title . '</strong>']);
    }
}