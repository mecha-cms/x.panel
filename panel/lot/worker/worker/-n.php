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
$__menus = array_replace_recursive($__menus, a(Config::get('panel.n', [])));
if ($__menus) {
    foreach (Anemon::eat($__menus)->sort([1, 'stack'], "")->vomit() as $__k => $__v) {
        if ($__k === 'n' || ($__v !== '0' && empty($__v)) || !isset($__v['stack']) || !is_numeric($__v['stack'])) {
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
            $__c = (is_array($__v) && isset($__v['is']['active']) && $__v['is']['active']) || strpos($__pth . '/', '::/' . $__k . '/') !== false ? ' is-current' : "";
            $__i = isset($__v['i']) ? ' <i>' . $__v['i'] . '</i>' : "";
            $__html .= '<li class="n-' . $__k . $__c . '">';
            if (is_array($__v)) {
                $__html .= HTML::a((isset($__v['text']) ? $__v['text'] : $language->{$__k}) . $__i, isset($__v['url']) ? $__v['url'] : $__a, false, isset($__v['attributes']) ? $__v['attributes'] : []);
            } else {
                $__html .= '<a href="' . $__a . '">' . $language->{$__k} . $__i . '</a>';
            }
            $__html .= '</li>';
        }
    }
    if (!empty($__menus['n'])) {
        $__html .= '<li class="n-n"><a href="">&#x22EE;</a><ul>';
        $__menus['n'] = Anemon::eat($__menus['n'])->sort([1, 'stack'], 10)->vomit();
        foreach ($__menus['n'] as $__kk => $__vv) {
            if ($__vv !== '0' && empty($__vv)) continue;
            if (is_string($__vv) && strpos($__vv, '<') === 0 && substr($__vv, -1) === '>' && strpos($__vv, '</') !== false) {
                if (substr($__vv, -5) === '</li>') {
                    $__html .= $__vv;
                } else {
                    $__html .= '<li class="n-' . md5($__vv) . '>' . $__vv . '</li>';
                }
            } else {
                $__kk = is_numeric($__kk) && is_string($__vv) ? $__vv : $__kk;
                $__aa = $__d . $__kk;
                $__cc = (is_array($__vv) && isset($__vv['is']['active']) && $__vv['is']['active']) || strpos($__pth . '/', '::/' . $__kk . '/') !== false ? ' is-current' : "";
                $__ii = isset($__vv['i']) ? ' <i>' . $__vv['i'] . '</i>' : "";
                $__html .= '<li class="n-n-' . $__kk . $__cc . '">';
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