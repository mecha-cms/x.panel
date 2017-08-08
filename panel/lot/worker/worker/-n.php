<?php

$__html  = '<nav class="n">';
$__html .= '<ul>';
$__pth = $url->path;
$__menus = [];
$__d = $url . '/' . $__state->path . '/::g::/';
foreach (glob(LOT . DS . '*', GLOB_ONLYDIR) as $__k => $__v) {
    $__v = basename($__v);
    $__menus[$__v] = [
        'url' => $__d . $__v,
        'stack' => ($__k + 1) * 10
    ];
}
$__menus = (array) a(Config::set('panel.n', $__menus)->get('panel.n', []));
if ($__menus) {
    $__menus = Anemon::eat($__menus)->not(function($__v) {
        return $__v !== '0' && (!isset($__v['stack']) || !is_numeric($__v['stack']));
    })->sort([1, 'stack'], "")->vomit();
    foreach ($__menus as $__k => $__v) {
        if ($__k === '+' || (isset($__v['is']['hidden']) && $__v['is']['hidden'])) {
            continue;
        }
        if (is_string($__v) && strpos($__v, '<') === 0 && substr($__v, -1) === '>' && strpos($__v, '</') !== false) {
            if (substr($__v, -5) === '</li>') {
                $__html .= $__v;
            } else {
                $__html .= '<li class="n-' . md5($__v) . '>' . $__v . '</li>';
            }
        } else {
            $__k = is_numeric($__k) && is_string($__v) ? $__v : $__k;
            $__a = $__d . $__k;
            $__c = (is_array($__v) && isset($__v['is']['active']) && $__v['is']['active']) || strpos($__pth . '/', '::/' . $__k . '/') !== false ? ' is.active' : "";
            $__i = isset($__v['i']) ? ' <i>' . $__v['i'] . '</i>' : "";
            $__html .= '<li class="n:' . $__k . $__c . '">';
            if (is_array($__v)) {
                $__html .= HTML::a((isset($__v['text']) ? $__v['text'] : $language->{$__k}) . $__i, isset($__v['url']) ? $__v['url'] : $__a, false, isset($__v['attributes']) ? $__v['attributes'] : []);
            } else {
                $__html .= '<a href="' . $__a . '">' . $language->{$__k} . $__i . '</a>';
            }
            $__v['+'] = !empty($__v['+']) ? Anemon::eat($__v['+'])->not(function($__v) {
                return $__v !== '0' && (!isset($__v['stack']) || !is_numeric($__v['stack']));
            })->vomit() : [];
            if (!empty($__v['+'])) {
                $__html .= '<ul>';
                foreach ($__v['+'] as $__kk => $__vv) {
                    if (isset($__vv['is']['hidden']) && $__vv['is']['hidden']) {
                        continue;
                    }
                    if (is_string($__vv) && strpos($__vv, '<') === 0 && substr($__vv, -1) === '>' && strpos($__vv, '</') !== false) {
                        if (substr($__vv, -5) === '</li>') {
                            $__html .= $__vv;
                        } else {
                            $__html .= '<li class="n:' . md5($__vv) . '>' . $__vv . '</li>';
                        }
                    } else {
                        $__kk = is_numeric($__kk) && is_string($__vv) ? $__vv : $__kk;
                        $__aa = $__d . $__kk;
                        $__cc = (is_array($__vv) && isset($__vv['is']['active']) && $__vv['is']['active']) || strpos($__pth . '/', '::/' . $__kk . '/') !== false ? ' is.active' : "";
                        $__ii = isset($__vv['i']) ? ' <i>' . $__vv['i'] . '</i>' : "";
                        $__html .= '<li class="n:' . $__k . '.' . $__kk . $__cc . '">';
                        if (is_array($__vv)) {
                            $__html .= HTML::a((isset($__vv['text']) ? $__vv['text'] : $language->{$__kk}) . $__ii, isset($__vv['url']) ? $__vv['url'] : $__aa, false, isset($__vv['attributes']) ? $__vv['attributes'] : []);
                        } else {
                            $__html .= '<a href="' . $__aa . '">' . $language->{$__kk} . $__ii . '</a>';
                        }
                        $__html .= '</li>';
                    }
                }
                $__html .= '</ul>';
            }
            $__html .= '</li>';
        }
    }
    if (!empty($__menus['+'])) {
        $__html .= '<li class="n:+"><a href="">&#x22EE;</a><ul>';
        $__menus['+'] = Anemon::eat($__menus['+'])->not(function($__v) {
            return $__v !== '0' && (!isset($__v['stack']) || !is_numeric($__v['stack']));
        })->sort([1, 'stack'], 10)->vomit();
        foreach ($__menus['+'] as $__kk => $__vv) {
            if (isset($__vv['is']['hidden']) && $__vv['is']['hidden']) {
                continue;
            }
            if (is_string($__vv) && strpos($__vv, '<') === 0 && substr($__vv, -1) === '>' && strpos($__vv, '</') !== false) {
                if (substr($__vv, -5) === '</li>') {
                    $__html .= $__vv;
                } else {
                    $__html .= '<li class="n:+.' . md5($__vv) . '>' . $__vv . '</li>';
                }
            } else {
                $__kk = is_numeric($__kk) && is_string($__vv) ? $__vv : $__kk;
                $__aa = $__d . $__kk;
                $__cc = (is_array($__vv) && isset($__vv['is']['active']) && $__vv['is']['active']) || strpos($__pth . '/', '::/' . $__kk . '/') !== false ? ' is.active' : "";
                $__ii = isset($__vv['i']) ? ' <i>' . $__vv['i'] . '</i>' : "";
                $__html .= '<li class="n:+.' . $__kk . $__cc . '">';
                if (is_array($__vv)) {
                    $__html .= HTML::a((isset($__vv['text']) ? $__vv['text'] : $language->{$__kk}) . $__ii, isset($__vv['url']) ? $__vv['url'] : $__aa, false, isset($__vv['attributes']) ? $__vv['attributes'] : []);
                } else {
                    $__html .= '<a href="' . $__aa . '">' . $language->{$__kk} . $__ii . '</a>';
                }
                $__html .= '</li>';
            }
        }
        $__html .= '</ul></li>';
    }
}
$__html .= '</ul></nav>';
return $__html;