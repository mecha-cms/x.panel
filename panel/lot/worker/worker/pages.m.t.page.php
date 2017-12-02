<?php

$__html = "";

if ($__pages[0]) {

    $__c = Shield::state($config->shield, 'path', Extend::state('page', 'path'));
    $__current = strpos($__path, '/') !== false ? substr($__path, strpos($__path, '/')) : "";

    foreach($__pages[0] as $__k => $__v) {

        if (!$__v->path) continue;

        $__vv = $__pages[1][$__k];

        $__s = 'panel.v.f.' . md5($__v->path);
        $__is_v = Session::get($__s);
        Session::reset($__s); // remember once!

        $__uu = $__v->url;

        $__pp = $__v->path;
        $__ppp = explode('/', Path::F($__pp, null, '/'));
        $__pppp = Config::get('panel.v.' . $__chops[0] . '.is.hidden', array_pop($__ppp)) === array_pop($__ppp) && file_exists(Path::D($__pp) . '.' . Path::X($__pp));

        $__as = [
            'edit' => $__pppp ? false : [$language->edit, $__uu . $__query]
        ];

        $__is_pages = !!g(LOT . explode('::' . $__command . '::', $__uu, 2)[1], 'draft,page,archive');  
        if ($__s = Config::get('panel.v.' . $__chops[0] . '.is.pages', "")) {
            $__is_pages = strpos(',' . $__s . ',', ',' . $__v->slug . ',') !== false;
        }

        if ($__is_pages) {
            $__as['get'] = [$language->open, $__uu . '/1' . $__query];
        }

        $__as['reset'] = [$language->delete, str_replace('::g::', '::r::', $__uu) . HTTP::query(['token' => $__token]), false, ['title' => $__pppp ? $language->o_toggle->as_pages : null]];

        $__as = Hook::fire('panel.a.' . $__chops[0], [$__as, [$__v, $__vv], $__pages]);

        $__cc = $__chops[0] . ' as.' . $__v->state . ($__pppp ? ' is.hidden' : "") . ($__v->status !== null ? ' status.' . $__v->status : "");
        if (Config::get('panel.v.' . $__chops[0] . '.as', $__c) === ltrim($__current . '/' . $__v->slug, '/')) {
            $__cc .= ' as.';
        }
        if (!$__is_pages || file_exists(Path::F($__pp) . DS . Path::N($__pp))) {
            $__cc .= ' is.page';
        }
        if ($__is_pages) {
            $__cc .= ' is.pages';
        }
        if ($__is_v) {
            $__cc .= ' v is.active';
        }

        $__html .= '<article class="' . $__cc . '" id="' . $__chops[0] . '-' . $__v->id . '">';
        if ($__vv->image) {
            $__html .= '<figure>';
            $__html .= '<a href="' . $__vv->image . '" target="_blank">';
            $__html .= '<img alt="" src="' . $__vv->image . '">';
            $__html .= '</a>';
            $__html .= '</figure>';
        }
        $__html .= '<header>';
        $__html .= '<h3>';
        if ($__v->state === 'draft' || $__vv->url === false) {
            $__html .= $__vv->title;
        } else {
            $__html .= HTML::a($__vv->title, $__vv->url, true);
        }
        $__html .= '</h3>';
        $__html .= '</header>';
        if ($__vv->description) {
            $__html .= '<section>';
            $__html .= '<p>' . To::snippet($__vv->description, true, [$__vv->image ? 150 : 250,'&#x2026;']) . '</p>';
            $__html .= '</section>';
        }
        $__html .= '<footer>';

        $__a = [];
        foreach ($__as as $__kk => $__vv) {
            if (!isset($__vv)) continue;
            if ($__vv && is_string($__vv) && $__vv[0] === '<' && strpos($__vv, '</') !== false && substr($__vv, -1) === '>') {
                $__a[$__kk] = $__vv;
            } else if (is_array($__vv)) {
                $__a[$__kk] = call_user_func_array('HTML::a', $__vv);
            }
        }

        $__html .= implode(' &#x00B7; ', (array) $__a);

        $__html .= '</footer>';
        $__html .= '</article>';

    }

} else {

    if ($__q = Request::get('q')) {
        $__html .= '<p>' . $language->message_error_search('<em>' . $__q . '</em>') . '</p>';
    } else {
        $__html .= '<p>' . ($site->__step === 1 ? $language->message_info_void($language->{$__chops[0] . 's'}) : To::sentence($language->_finded)) . '</p>';
    }

}

return $__html;