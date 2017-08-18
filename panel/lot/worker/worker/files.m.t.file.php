<?php

$__html = "";

if ($__files[0]) {

    foreach ($__files[0] as $__k => $__v) {

        $__vv = $__files[1][$__k];

        $__s = 'panel.v.f.' . md5($__v->path);
        $__is_v = Session::get($__s);
        Session::reset($__s); // remember once!

        $__html .= '<article class="' . $__chops[0] . ' is.' . ($__v->is->file ? 'file' : 'files is.folder') . ($__v->is->hidden ? ' is.hidden' : "") . ($__is_v ? ' v is.active' : "") . '">';

        $__u = $url . '/' . $__state->path . '/::g::/';
        $__uu = $__u . str_replace([LOT . DS, DS], ["", '/'], $__v->path);

        $__html .= '<header>';
        $__html .= '<h3>';
        if ($__v->is->file) {
            $__html .= '<span class="a" title="' . $language->size . ': ' . $__v->size . '">' . $__vv->title . '</span>';
        } else {
            $__html .= '<a href="' . $__v->url . $__query . '" title="' . ($__ii = count(glob($__v->path . DS . '*', GLOB_NOSORT))) . ' ' . $language->{$__ii === 1 ? 'item' : 'items'} . '">' . $__vv->title . '</a>';

            /*
            if ($__v->is->files && count(glob($__v->path . DS . '*', GLOB_ONLYDIR | GLOB_NOSORT)) === 1 && $__g = File::explore($__v->path, true)) {
                $__dd = $__ff = [];
                foreach ($__g as $__kkk => $__vvv) {
                    $__kkkk = basename($__kkk);
                    if ($__vvv === 0) {
                        $__uu .= '/' . $__kkkk;
                        if (count(glob($__kkk . DS . '*', GLOB_ONLYDIR | GLOB_NOSORT)) <= 1) {
                            $__dd[] = $__uu;
                            $__html .= ' / ' . HTML::a($__kkkk, $__uu . $__is_has_step);
                        }
                    } else {
                        $__ff[] = $__uu . '/' . $__kkkk;
                    }
                }
                if (count($__ff) === 1 && $__dd && dirname(end($__dd)) !== dirname($__ff[0])) {
                    $__fff = basename($__ff[0]);
                    $__uu .= '/' . $__fff;
                    $__html .= ' / ' . HTML::a($__fff, $__ff[0]);
                    $__v->is->file = true;
                }
                $__v->url = $__uu;
                $__vv->url = To::url(str_replace($__u, LOT . DS, $__uu));
            }
            */

        }
        $__html .= '</h3>';
        $__html .= '</header>';
        $__html .= '<section></section>';
        $__html .= '<footer>';

        $__as = [
            'view' => $__v->is->file ? [$language->view, $__vv->url, true] : false,
            'set' => $__v->is->file ? false : [$language->add, str_replace('::g::', '::s::', $__uu) . $__query],
            'edit' => [$language->edit, $__uu . $__query],
            'reset' => [$language->delete, str_replace('::g::', '::r::', $__v->url . HTTP::query(['token' => $__token]))]
        ];

        $__as = Hook::fire('panel.a.' . $__chops[0], [$__as, [$__v, $__vv], $__files]);

        $__a = [];
        foreach ($__as as $__k => $__v) {
            if (!isset($__v)) continue;
            if ($__v && is_string($__v) && $__v[0] === '<' && strpos($__v, '</') !== false && substr($__v, -1) === '>') {
                $__a[$__k] = $__v;
            } else if (is_array($__v)) {
                $__a[$__k] = call_user_func_array('HTML::a', $__v);
            }
        }

        $__html .= implode(' &#x00B7; ', (array) $__a);

        $__html .= '</footer>';
        $__html .= '</article>';

    }

    // Breadcrumb(s)…
    if (count($__chops) > 1) {
        $__html .= '<nav>';
        $__chops_c = $__chops;
        $__chops_e = array_pop($__chops_c);
        $__uu = $__u . array_shift($__chops_c);
        $__s = HTML::a($__chops[0], $__uu);
        foreach ($__chops_c as $__k => $__v) {
            $__uu .= '/' . $__v;
            $__s .= ' / ' . HTML::a($__v, $__uu . $__is_has_step . $__query);
        }
        $__html .= $__s . ' / ' . $__chops_e;
        $__html .= '</nav>';
    }

} else {

    if ($__q = Request::get('q')) {
        $__html .= '<p>' . $language->message_error_search('<em>' . $__q . '</em>') . '</p>';
    } else {
        $__html .= '<p>' . (is_dir(LOT . DS . $__path) && $site->__step === 1 ? $language->message_info_void($language->{(count($__chops) === 1 ? $__chops[0] : 'file') . 's'}) : To::sentence($language->_finded)) . '</p>';
    }

}

return $__html;