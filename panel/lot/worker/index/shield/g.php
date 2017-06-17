<?php

if (count($__chops) <= 1) {
    if ($__f = glob(SHIELD . DS . '*', GLOB_ONLYDIR)) {
        if ($__q = l(Request::get('q', ""))) {
            $__q = explode(' ', $__q);
            $__f = array_filter($__f, function($__v) use($__q) {
                $__v = Path::N($__v);
                foreach ($__q as $_) {
                    if (strpos($__v, $_) !== false) {
                        return true;
                    }
                }
                return false;
            });
        }
        foreach ($__f as $__v) {
            $__id = Path::B($__v);
            if (substr($__id, -6) === '.trash') continue;
            if ($__v = File::exist([
                $__v . DS . 'about.' . $config->language . '.page',
                $__v . DS . 'about.page'
            ])) {
                $__a = new Page($__v, [], '__page');
                $__b = new Page($__v, []);
                $__a->id = $__b->id = $__id;
                $__pages[0][] = $__a;
                $__pages[1][] = $__b;
            }
        }
    }
    Lot::set([
        '__pages' => $__pages,
        '__pager' => [new Elevator($__f ?: [], $__chunk, $__step, $url . '/' . $__state->path . '/::g::/' . $__path, [
            'direction' => [
               '-1' => 'previous',
                '0' => false,
                '1' => 'next'
            ],
            'union' => [
               '-2' => [
                    2 => ['rel' => null, 'classes' => ['button', 'x']]
                ],
               '-1' => [
                    1 => '&#x276E;',
                    2 => ['rel' => 'prev', 'classes' => ['button']]
                ],
                '1' => [
                    1 => '&#x276F;',
                    2 => ['rel' => 'next', 'classes' => ['button']]
                ]
            ]
        ], '__pages')],
        '__is_has_step_page' => count($__f) > $__chunk
    ]);
} else {
    if ($__f = File::exist([
        LOT . DS . $__path . DS . 'about.' . $config->language . '.page',
        LOT . DS . $__path . DS . 'about.page'
    ])) {
        $__page = [
            new Page($__f, [], '__page'),
            new Page($__f, [], 'page')
        ];
        Lot::set('__page', $__page);
    } else if ($__f = File::exist(LOT . DS . $__path)) {
        if ($__is_post && !Message::$x) {
            if (Request::post('xx') === -1) {
                Guardian::kick(str_replace('::g::', '::r::', $url->current . HTTP::query(['token' => Request::post('token')])));
            }
            $__n = Request::post('name');
            $__x = Path::X($__n, false);
            $__is_f = Request::post('xx') !== 0;
            if (!$__n) {
                Message::error('void_field', $language->name, true);
            } else if ($__is_f) {
                if ($__x === false) {
                    Message::error('void_field', $language->extension, true);
                } else if (!Is::these(File::$config['extensions'])->has($__x)) {
                    Message::error('file_x', '<em>' . $__x . '</em>');
                }
            }
            $__ff = SHIELD . DS . $__chops[1] . DS . call_user_func('To::' . ($__is_f ? 'file' : 'folder'), $__n);
            Hook::fire('on.shield.set', [$__ff]);
            if (!Message::$x) {
                if ($__is_f) {
                    File::open($__f)->delete();
                    File::write(Request::post('content'))->saveTo($__ff);
                } else {
                    File::open($__f)->moveTo($__ff);
                }
                Message::success(To::sentence($language->updateed));
                Guardian::kick($__state->path . '/::g::/' . $__chops[0] . '/' . $__chops[1] . '/' . To::url($__n));
            } else {
                Request::save('post');
            }
        }
        if (Is::F($__f)) {
            $__x = Path::X($__f);
            $__a = array_replace(File::inspect($__f), [
                'name' => str_replace(SHIELD . DS . $__chops[1] . DS, "", To::path($__f)),
                'type' => 'HTML'
            ]);
        } else {
            $__a = array_replace(File::inspect($__f), [
                'name' => str_replace(SHIELD . DS . $__chops[1] . DS, "", To::path($__f)),
                'type' => 'HTML'
            ]);
        }
        $__a = o($__a);
        Lot::set('__page', [$__a, $__a]);
    } else {
        Shield::abort(PANEL_404);
    }
    if ($__f = glob(SHIELD . DS . '*', GLOB_ONLYDIR)) {
        foreach ($__f as $__k => $__v) {
            if (Path::N($__v) === $__chops[1]) continue;
            if ($__v = File::exist([
                $__v . DS . 'about.' . $config->language . '.page',
                $__v . DS . 'about.page'
            ])) {
                $__kins[0][] = new Page($__v, [], '__page');
                $__kins[1][] = new Page($__v, [], 'page');
            }
        }
        $__is_has_step_kin = count($__f) > $__chunk;
        Lot::set([
            '__kins' => $__kins,
            '__is_has_step_kin' => $__is_has_step_kin
        ]);
    }
    if ($__f = glob(LOT . DS . $__path . DS . '*')) {
        foreach ($__f as $__k => $__v) {
            $__b = str_replace(SHIELD . DS . $__chops[1] . DS, "", To::path($__v));
            if (substr($__b, -6) === '.trash') continue;
            $__a = [
                'title' => Path::B($__b),
                'path' => $__v,
                'extension' => Path::X($__v),
                'url' => $url . '/' . $__state->path . '/::g::/' . $__chops[0] . '/' . $__chops[1] . '/' . $__b
            ];
            $__datas[0][] = (object) $__a;
            $__datas[1][] = (object) $__a;
        }
        Lot::set('__datas', $__datas);
    }
}