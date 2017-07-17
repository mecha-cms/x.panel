<?php

// NOTE: These helper function(s) are only used internally and does
// not intended to be used in production or sny other extension(s).

function __panel_a__($k, $v) {
    // `id`
    // `key`
    // `picture`
    // `title`
    // `description`
    // `snippet`
    // `union` ['article']
    // `a`
    // `if`
    // `is`
    //    `hidden`
    //    `visible`
    // `as`
    // `on`
    // `attributes`
    if (isset($v['if']) && !$v['if']) {
        return "";
    }
    if (isset($v['is']['hidden']) && $v['is']['hidden'] || isset($v['is']['visible']) && !$v['is']['visible']) {
        return "";
    }
    if (is_string($v)) {
        return $v;
    } else if (!$v) {
        return "";
    }
    $a = ['classes' => ['page', $k ? 'page-' . $k : null]];
    if (!empty($v['is'])) {
        if (__is_anemon_a__($v['is'])) {
            $v['is'] = array_keys(array_filter($v['is']));
        }
        foreach ($v['is'] as $vv) {
            $a['classes'][] = 'is-' . $vv;
        }
    }
    if (!empty($v['as'])) {
        if (__is_anemon_a__($v['as'])) {
            $v['as'] = array_keys(array_filter($v['as']));
        }
        foreach ($v['as'] as $vv) {
            $a['classes'][] = 'as-' . $vv;
        }
    }
    if (!empty($v['on'])) {
        if (__is_anemon_a__($v['on'])) {
            $v['on'] = array_keys(array_filter($v['on']));
        }
        foreach ($v['on'] as $vv) {
            $a['classes'][] = 'on-' . $vv;
        }
    }
    $aa = array_replace_recursive($a, isset($v['attributes']) ? (array) $v['attributes'] : []);
    if (isset($v['id']) && !array_key_exists('id', $aa)) {
        $aa['id'] = $v['id'];
    }
    $union = new Union([], 'h_t_m_l');
    $u = array_replace_recursive(['article', $aa], isset($v['union']) ? (array) $v['union'] : []);
    if ($u[0] !== 'article') {
        $u[1]['classes'][] = 'article';
    }
    $html  = call_user_func_array([$union, 'begin'], $u);
    if (isset($v['picture'])) {
      
    }
    if (isset($v['title'])) {
        $html .= '<header>';
        $html .= '<h3>';
        $html .= is_array($v['title']) ? call_user_func_array('HTML::a', $v['title']) : $v['title'];
        $html .= '</h3>';
        $html .= '</header>';
    }
    if (isset($v['description'])) {
        $html .= '<section>';
        $html .= '<p>' . call_user_func_array('To::snippet', array_merge([$v['description'], !empty($v['snippet']) ? $v['snippet'] : [true, Extend::state('panel', 'snippet')]])) . '</p>';
        $html .= '</section>';
    }
    if (!empty($v['a'])) {
        $html .= '<footer>';
        $html .= '<p>';
        $a = [];
        foreach ($v['a'] as $vv) {
            if (!isset($vv)) continue;
            if ($vv && is_string($vv) && $vv[0] === '<' && strpos($vv, '</') !== false && substr($vv, -1) === '>') {
                $a[] = $vv;
            } else {
                $a[] = call_user_func_array('HTML::a', $vv);
            }
        }
        $html .= implode(' &#x00B7; ', $a) . '</p>';
        $html .= '</footer>';
    }
    $html .= $union->end();
    return $html;
}

function __panel_f__($k, $v) {
    global $language;
    // `key`
    // `title`
    // `description`
    // `type`
    // `value`
    // `values`
    // `order`
    // `placeholder`
    // `pattern`
    // `range`
    // `step`
    // `union` ['p']
    // `if`
    // `is`
    //    `block`
    //    `expand`
    //    `hidden`
    //    `visible`
    // `attributes`
    // `expand`
    // `stack`
    if (isset($v['if']) && !$v['if']) {
        return "";
    }
    if (isset($v['is']['hidden']) && $v['is']['hidden'] || isset($v['is']['visible']) && !$v['is']['visible']) {
        return "";
    }
    if (is_string($v)) {
        return $v;
    } else if (!$v) {
        return "";
    }
    $union = new Union([], 'h_t_m_l');
    $type = isset($v['type']) ? $v['type'] : null;
    $kk = isset($v['key']) ? $v['key'] : ltrim($k, '.!*');
    $a = ['classes' => ['f', 'f-' . $kk]];
    $aa = isset($v['attributes']) ? (array) $v['attributes'] : [];
    $q = 'f.' . str_replace('][', '.', trim($k, ']['));
    if (isset($v['pattern'])) {
        $aa['pattern'] = $v['pattern'];
    }
    if (isset($v['expand']) && $v['expand'] || ($type && ($type === 'submit' || $type === 'submit[]') && !isset($v['expand']))) {
        $a['classes'][] = 'expand';
    }
    $u = array_replace_recursive(['p', $a], isset($v['union']) ? (array) $v['union'] : []);
    if ($u[0] !== 'p') {
        $u[1]['classes'][] = 'p';
    }
    $title = isset($v['title']) ? $v['title'] : $language->{$kk};
    $text = isset($v['text']) ? $v['text'] : $title;
    $html  = call_user_func_array([$union, 'begin'], $u);
    $html .= $union->unite('label', $title, ['for' => 'f-' . $kk]);
    $html .= $union->begin($u[0] === 'p' ? 'span' : $u[0]);
    $value = isset($v['value']) ? $v['value'] : null;
    $placeholder = array_key_exists('placeholder', $v) ? $v['placeholder'] : $value;
    $hidden = false;
    if ($type) {
        $is_block = (isset($v['is']['block']) && $v['is']['block'] || ($type === 'textarea' || $type === 'editor') && (!isset($v['is']['block']) || $v['is']['block'])) ? 'block' : null;
        $is_expand = isset($v['is']['expand']) && $v['is']['expand'] ? 'expand' : null;
        if ($type === 'hidden') {
            $hidden = Form::hidden($k, $v, $aa);
        } else if (strpos(X . 'button' . X . 'button[]' . X . 'reset' . X . 'reset[]' . X . 'submit' . X . 'submit[]' . X, X . $type . X) !== false) {
            $type = str_replace('[]', "", $type);
            if (isset($v['values'])) {
                if (isset($v['order'])) {
                    $vvv = [];
                    $vvv_k = array_keys($v['values']);
                    $vvv_v = array_flip($vvv_k);
                    foreach ($v['order'] as $vv) {
                        if ($vv === null || !isset($vvv_v[$vv])) continue;
                        $vvv[$vv] = $v['values'][$vv];
                    }
                } else {
                    $vvv = $v['values'];
                }
                foreach ($vvv as $nn => $vv) {
                    if (!isset($vv)) continue;
                    if ($vv && is_string($vv) && $vv[0] === '<' && strpos($vv, '</') !== false && substr($vv, -1) === '>') {
                        $html .= $vv;
                    } else {
                        $vv = (array) $vv;
                        if (isset($vv[1])) {
                            // 'name (default)' => [
                            //     'values' => [
                            //         'name_1' => ['text 1', 'value_1'],
                            //         'name_2' => ['text 2', 'value_2']
                            //     ]
                            // ]
                            $k = $n = $nn;
                            $nn = $vv[1] === true ? $n : $vv[1];
                            $vv = $vv[0];
                        } else {
                            // 'name' => [
                            //     'values' => [
                            //         'value_1' => 'text 1',
                            //         'value_2' => 'text 2'
                            //     ]
                            // ]
                            $vv = $vv[0];
                        }
                        $html .= call_user_func('Form::' . $type, $k, ltrim(Request::get($q, $nn), '.!*'), $vv, array_replace_recursive(['classes' => ['button'], 'id' => 'f-' . $kk . ':' . $nn], $aa)) . ' ';
                    }
                }
                $html = rtrim($html, ' ');
            } else {
                if ($value && is_string($value) && $value[0] === '<' && strpos($value, '</') !== false && substr($value, -1) === '>') {
                    $html .= $value;
                } else {
                    $html .= call_user_func('Form::' . $type, $k, Request::get($q, isset($value) ? $value : true), $text, array_replace_recursive(['classes' => ['button'], 'id' => 'f-' . $kk], $aa));
                }
            }
        } else if ($type === 'content') {
            $html .= $value;
        } else if ($type === 'textarea') {
            $html .= Form::textarea($k, Request::get($q, is_array($value) ? json_encode($value) : $value, false), $placeholder, array_replace_recursive(['classes' => ['textarea', $is_block, $is_expand], 'id' => 'f-' . $kk], $aa));
        } else if ($type === 'editor') {
            $html .= Form::textarea($k, Request::get($q, is_array($value) ? json_encode($value) : $value, false), $placeholder, array_replace_recursive(['classes' => ['textarea', $is_block, $is_expand, 'code', 'editor'], 'id' => 'f-' . $kk], $aa));
        } else if ($type === 'query') {
            $html .= Form::text($k, Request::get($q, is_array($value) ? implode(', ', $value) : $value, false), $placeholder, array_replace_recursive(['classes' => ['input', $is_block, 'query'], 'id' => 'f-' . $kk], $aa));
        } else if ($type === 'date') {
            $ff = 'Y/m/d H:i:s';
            $html .= Form::text($k, (new Date(Request::get($q, is_array($value) ? json_encode($value) : $value, false)))->format($ff), (new Date($placeholder))->format($ff), array_replace_recursive(['classes' => ['input', $is_block, 'date'], 'id' => 'f-' . $kk], $aa));
        } else if ($type === 'range') {
            if (isset($v['range'])) {
                // [min, val, max]
                $value = [$v['range'][0], $value, $v['range'][1]];
            }
            if (isset($v['step'])) {
                $aa['step'] = $v['step'];
            }
            $html .= Form::range($k, $value, array_replace_recursive(['classes' => ['input'], 'id' => 'f-' . $kk], $aa));
        // TODO
        // } else if ($type === 'color') {
        //
        } else if (($type === 'select' || $type === 'select[]') && isset($v['values'])) {
            $vv = (array) $v['values'];
            if (isset($v['placeholder'])) {
                $vv = ['.' => $v['placeholder']] + $vv;
            }
            $html .= Form::select($k, $vv, Request::get($q, $value), array_replace_recursive(['classes' => ['select', $is_block], 'id' => 'f-' . $kk, 'multiple' => $type === 'select[]' ? true : null], $aa));
        } else if ($type === 'toggle' || $type === 'toggle[]') {
            if (isset($v['values'])) {
                $vv = (array) $v['values'];
                asort($vv);
                if ($type === 'toggle[]') {
                    $hh = "";
                    if (empty($value) || __is_anemon_0__($value)) {
                        $rr = X . implode(X, (array) Request::get($q, $value)) . X;
                        foreach ($vv as $kkk => $vvv) {
                            $hh .= '<br>' . Form::checkbox($k . '[]', $kkk, strpos($rr, X . $kkk . X) !== false, $vvv, array_replace_recursive(['classes' => ['input'], 'id' => 'f-' . $kk . ':' . $kkk], $aa));
                        }
                    } else {
                        foreach ($vv as $kkk => $vvv) {
                            $vvv = (array) $vvv;
                            // 'name (default)' => [
                            //     'values' => [
                            //         'name_1' => ['text 1', 'value_1'],
                            //         'name_2' => ['text 2', 'value_2']
                            //     ]
                            // ]
                            //
                            // 'name' => [
                            //     'values' => [
                            //         'value_1' => 'text 1',
                            //         'value_2' => 'text 2'
                            //     ]
                            // ]
                            $hh .= '<br>' . Form::checkbox($k . '[' . $kkk . ']', is_array($vvv) && isset($vvv[1]) ? $vvv[1] : true, !empty(Request::get('f.' . str_replace('][', '.', trim($kkk, '][')), isset($value[$kkk]) ? $value[$kkk] : false, false)), $vvv[0], array_replace_recursive(['classes' => ['input'], 'id' => 'f-' . $kk . ':' . $kkk], $aa));
                        }
                    }
                    $html .= substr($hh, 4);
                } else {
                    $html .= Form::radio($k, $vv, Request::get($q, $value), array_replace_recursive(['classes' => ['input'], 'id' => 'f-' . $kk], $aa));
                }
            } else {
                $html .= Form::checkbox($k, isset($value) ? $value : 'true', $value !== null && Request::get($q) === $value, $text, array_replace_recursive(['classes' => ['input'], 'id' => 'f-' . $kk], $aa));
            }
        } else if ($type === 'file') {
            $html .= Form::file($k, array_replace_recursive(['classes' => ['input'], 'id' => 'f-' . $kk], $aa));
        } else if (strpos(',color,email,number,pass,password,search,tel,text,url,', ',' . $type . ',') !== false) {
            if ($type === 'pass') {
                $type .= 'word';
            }
            $html .= call_user_func('Form::' . $type, $k, Request::get($q, $value, false), $placeholder, array_replace_recursive(['classes' => ['input', $is_block], 'id' => 'f-' . $kk], $aa));
        }
    } else {
        $html .= Form::textarea($k, Request::get($q, is_array($value) ? json_encode($value) : $value, false), $placeholder, array_replace_recursive(['classes' => ['textarea'], 'id' => 'f-' . $kk], $aa));
    }
    $html .= $union->end();
    $html .= $union->end();
    if (!empty($v['description'])) {
        $description = $v['description'];
        $html .= '<div class="h p">' . (stripos($description, '</p>') === false ? '<p>' . $description . '</p>' : $description) . '</div>';
    }
    return $hidden ?: $html;
}

function __panel_m__() {}
function __panel_n__() {}

function __panel_s__($k, $v, $i = '%{0}%', $j = "") {
    // `title`
    // `description`
    // `content`
    // `before`
    // `after`
    // `a`
    // `if`
    // `is`
    //    `hidden`
    //    `visible`
    // `stack`
    global $language;
    if (is_string($v)) {
        return $v;
    } else if (!$v) {
        return "";
    }
    if (isset($v['if']) && !$v['if']) {
        return "";
    }
    if (isset($v['is']['hidden']) && $v['is']['hidden'] || isset($v['is']['visible']) && !$v['is']['visible']) {
        return "";
    }
    $content = isset($v['content']) ? $v['content'] : [];
    $html  = '<section class="s-' . $k . '">';
    $html .= '<h3>' . (isset($v['title']) ? $v['title'] : $language->{isset($v['content'][0]) && count($v['content'][0]) === 1 ? $k : $k . 's'}) . '</h3>';
    if (isset($v['before']) && $v['before']) {
        $html .= $v['before'];
    }
    if (!empty($v['content'])) {
        if (is_array($v['content'])) {
            $html .= '<ul>';
            if (is_array($v['content'][0])) {
                foreach ($v['content'][0] as $kk => $vv) {
                    $html .= is_callable($j) ? call_user_func($j, '<li>', $vv, $kk) : '<li>';
                    if (is_object($vv)) {
                        $w = $vv->url;
                        if (is_callable($i)) {
                            $w = call_user_func($i, $vv, $kk);
                        } else {
                            $w = __replace__($i, $w);
                        }
                        $html .= HTML::a($v['content'][1][$kk]->title, $w);
                    } else if (is_array($vv)) {
                        $w = $vv['url'];
                        if (is_callable($i)) {
                            $w = call_user_func($i, $vv, $kk);
                        } else {
                            $w = __replace__($i, $w);
                        }
                        $html .= HTML::a($v['content'][1][$kk]['title'], $w);
                    } else {
                        if (is_callable($i)) {
                            $vv = call_user_func($i, $vv, $kk);
                        } else {
                            $vv = __replace__($i, $vv);
                        }
                        $html .= $vv;
                    }
                    $html .= '</li>';
                }
            }
            if (isset($v['a']) && $v['a']) {
                $a = [];
                foreach ($v['a'] as $kk => $vv) {
                    if (!isset($vv)) continue;
                    if ($vv && is_string($vv) && $vv[0] === '<' && strpos($vv, '</') !== false && substr($vv, -1) === '>') {
                        $a[] = $vv;
                    } else {
                        $a[] = call_user_func_array('HTML::a', $vv);
                    }
                }
                $html .= $a ? '<li>' . implode(' ', $a) . '</li>' : "";
            }
            $html .= '</ul>';
        } else if (is_string($v['content'])) {
            $html .= $v['content'];
        }
    }
    if (isset($v['after']) && $v['after']) {
        $html .= $v['after'];
    }
    $html .= '</section>';
    return $html;
}

function __panel_t__() {}