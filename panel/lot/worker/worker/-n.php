<?php

function _n_ul($__a, $__n) {
    if (empty($__a)) {
        return "";
    }
    global $language, $url, $__nav;
    $__p = $url->path;
    $__d = $url . '/' . explode('/', $__p)[0] . '/::g::/';
    $__s = '<ul class="' . $__n . '">';
    $__a = Anemon::eat($__a)->is(function($__v) {
        return isset($__v['stack']) && is_numeric($__v['stack']);
    })->sort([1, 'stack'], "")->vomit();
    foreach ($__a as $__k => $__v) {
        if (isset($__v['is']['hidden']) && $__v['is']['hidden']) {
            continue;
        }
        if (is_string($__v) && strpos($__v, '<') === 0 && substr($__v, -1) === '>' && strpos($__v, '</') !== false) {
            if (substr($__v, -5) === '</li>') {
                $__s .= $__v;
            } else {
                $__s .= '<li class="' . $__n . ':' . md5($__v) . '>' . $__v . '</li>';
            }
        } else {
            $__k = is_numeric($__k) && is_string($__v) ? $__v : $__k;
            $__t = isset($__v['text']) ? $__v['text'] : $language->{$__k};
            $__a = isset($__v['url']) ? $__v['url'] : $__d . $__k;
            $__c = (is_array($__v) && isset($__v['is']['active']) && $__v['is']['active']) || strpos($__p . '/', '::/' . $__k . '/') !== false ? ' is.active' : "";
            if (!$__cc = empty($__v['+'])) {
                $__c .= ' is.parent';
            }
            $__r = isset($__v['attributes']) ? (array) $__v['attributes'] : [];
            $__i = isset($__v['i']) ? ' <i>' . $__v['i'] . '</i>' : "";
            $__s .= '<li class="' . $__n . ':' . $__k . $__c . '">';
            if (is_array($__v)) {
                if (isset($__v['description'])) {
                    $__v['attributes']['title'] = $__v['description'];
                }
                $__s .= HTML::a($__t . $__i, $__a, false, $__r);
                if (!$__cc) {
                    $__s .= is_array($__v['+']) ? _n_ul($__v['+'], $__n . '-n') : $__v['+'];
                }
            } else {
                $__h = isset($__v['description']) ? ' title="' . htmlentities($__v['description']) . '"' : "";
                $__s .= '<a href="' . $__a . '"' . $__h . '>' . $__t . $__i . '</a>';
            }
            $__s .= '</li>';
        }
    }
    return $__s .= '</ul>';
}

$__d = $url . '/' . $__state->path . '/::g::/';
$__menus = [];
foreach (glob(LOT . DS . '*', GLOB_ONLYDIR) as $__k => $__v) {
    $__v = basename($__v);
    $__menus[$__v] = [
        'url' => $__d . $__v,
        'stack' => ($__k + 1) * 10
    ];
}
$__menus = (array) a(Config::set('panel.n', $__menus)->get('panel.n', []));

$__vv = (array) a(Config::get('panel.v.n', []));
foreach ($__menus as $__k => &$__v) {
    if ($__k === '+') continue;
    $__v['is']['hidden'] = !isset($__vv[$__k]) || !$__vv[$__k];
}

return '<nav class="n">' . _n_ul($__menus, 'n') . '</nav>';