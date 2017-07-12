<?php

function __panel_a__($k, $v) {
    global $__state;
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
    if (array_key_exists('if', $v)) {
        $v['is']['visible'] = $v['if'];
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
    $union = new Union([], 'h_t_m_l');
    $u = array_replace_recursive(['article', $a], isset($v['union']) ? (array) $v['union'] : []);
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
        $html .= '<p>' . call_user_func_array('To::snippet', array_merge([$v['description'], !empty($v['snippet']) ? $v['snippet'] : [true, $__state->snippet]])) . '</p>';
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
    if (array_key_exists('if', $v)) {
        $v['is']['visible'] = $v['if'];
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
    $kk = isset($v['key']) ? $v['key'] : ltrim($k, '.!*');
    $a = ['classes' => ['f', 'f-' . $kk]];
    $aa = isset($v['attributes']) ? (array) $v['attributes'] : [];
    if (isset($v['pattern'])) {
        $aa['pattern'] = $v['pattern'];
    }
    if (isset($v['expand']) && $v['expand'] || (isset($v['type']) && strpos(',button,reset,submit,', ',' . $v['type'] . ',') !== false && !isset($v['expand']))) {
        $a['classes'][] = 'expand';
    }
    $u = array_replace_recursive(['p', $a], isset($v['union']) ? (array) $v['union'] : []);
    if ($u[0] !== 'p') {
        $u[1]['classes'][] = 'p';
    }
    $title = isset($v['title']) ? $v['title'] : $language->{$kk};
    $html  = call_user_func_array([$union, 'begin'], $u);
    $html .= $union->unite('label', $title, ['for' => 'f-' . $kk]);
    $html .= $union->begin($u[0] === 'p' ? 'span' : $u[0]);
    $value = isset($v['value']) ? $v['value'] : null;
    $placeholder = array_key_exists('placeholder', $v) ? $v['placeholder'] : $value;
    if (isset($v['type'])) {
        $type = $v['type'];
        $is_block = isset($v['is']['block']) && $v['is']['block'] ? 'block' : null;
        $is_expand = isset($v['is']['expand']) && $v['is']['expand'] ? 'expand' : null;
        if (strpos(',button,reset,submit,', ',' . $type . ',') !== false) {
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
                foreach ($vvv as $ii => $vv) {
                    if (!isset($vv)) continue;
                    if ($vv && is_string($vv) && $vv[0] === '<' && strpos($vv, '</') !== false && substr($vv, -1) === '>') {
                        $html .= $vv;
                    } else {
                        $html .= call_user_func('Form::' . $type, $k, ltrim($ii, '.!*'), $vv, array_replace_recursive(['classes' => ['button', 'f-' . $kk . ':' . $ii], 'id' => 'f-' . $kk . ':' . $ii], $aa)) . ' ';
                    }
                }
                $html = rtrim($html, ' ');
            } else {
                if ($value && is_string($value) && $value[0] === '<' && strpos($value, '</') !== false && substr($value, -1) === '>') {
                    $html .= $value;
                } else {
                    $html .= call_user_func('Form::' . $type, $k, isset($value) ? $value : 1, isset($v['text']) ? $v['text'] : $title, array_replace_recursive(['classes' => ['button', 'f-' . $kk], 'id' => 'f-' . $kk, 'type' => 'submit'], $aa));
                }
            }
        } else if ($type === 'content') {
            $html .= $value;
        } else if ($type === 'textarea') {
            $html .= Form::textarea($k, $value, $placeholder, array_replace_recursive(['classes' => ['textarea', $is_block, $is_expand], 'id' => 'f-' . $kk], $aa));
        } else if ($type === 'editor') {
            $html .= Form::textarea($k, $value, $placeholder, array_replace_recursive(['classes' => ['textarea', 'block', $is_expand, 'code', 'editor'], 'id' => 'f-' . $kk], $aa));
        } else if ($type === 'query') {
            $html .= Form::text($k, $value, $placeholder, array_replace_recursive(['classes' => ['input', $is_block, 'query'], 'id' => 'f-' . $kk], $aa));
        } else if ($type === 'date') {
            $ff = 'Y/m/d H:i:s';
            $html .= Form::text($k, (new Date($value))->format($ff), (new Date($placeholder))->format($ff), array_replace_recursive(['classes' => ['input', $is_block, 'date'], 'id' => 'f-' . $kk], $aa));
        // TODO
        // } else if ($type === 'color') {
        //
        } else if ($type === 'select' && isset($v['values'])) {
            $vv = (array) $v['values'];
            if (isset($v['placeholder'])) {
                $vv = array_merge(['.' => $v['placeholder']], $vv);
            }
            $html .= Form::select($k, $vv, $value, array_replace_recursive(['classes' => ['select', $is_block], 'id' => 'f-' . $kk], $aa));
        } else if ($type === 'toggle') {
            if (isset($v['values'])) {
                $vv = (array) $v['values'];
                $html .= Form::radio($k, $vv, $value, array_replace_recursive(['classes' => ['input'], 'id' => 'f-' . $kk], $aa));
            } else {
                $html .= Form::checkbox($k, isset($value) ? $value : 1, $value !== null, array_replace_recursive(['classes' => ['input'], 'id' => 'f-' . $kk], $aa));
            }
        } else if ($type === 'file') {
            $html .= Form::file($k, array_replace_recursive(['classes' => ['input'], 'id' => 'f-' . $kk], $aa));
        } else if (strpos(',color,email,number,password,search,tel,text,url,', ',' . $type . ',') !== false) {
            $html .= call_user_func('Form::' . $type, $k, $value, $placeholder, array_replace_recursive(['classes' => ['input', $is_block], 'id' => 'f-' . $kk], $aa));
        }
    } else {
        $html .= Form::textarea($k, $value, $placeholder, array_replace_recursive(['classes' => ['textarea', 'block'], 'id' => 'f-' . $kk], $aa));
    }
    $html .= $union->end();
    $html .= $union->end();
    if (!empty($v['description'])) {
        $description = $v['description'];
        $html .= '<div class="h p">' . (stripos($description, '</p>') === false ? '<p>' . $description . '</p>' : $description) . '</div>';
    }
    return $html;
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
    if (array_key_exists('if', $v)) {
        $v['is']['visible'] = $v['if'];
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