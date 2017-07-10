<?php

function __panel_a__($k, $v) {
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
    $html = $union->begin('article', []);
    // ...
}

function __panel_f__($k, $v) {
    global $language;
    // `key`
    // `title`
    // `description`
    // `type`
    // `value`
    // `values`
    // `placeholder`
    // `pattern`
    // `union` ['p']
    // `if`
    // `is`
    //    `block`
    //    `expand`
    //    `hidden`
    //    `visible`
    //    `.` <disabled>
    //    `!` <readonly>
    //    `*` <required>
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
    if (isset($v['is']['.']) && $v['is']['.']) {
        $aa['disabled'] = true;
    }
    if (isset($v['is']['*']) && $v['is']['*']) {
        $aa['required'] = true;
    }
    if (isset($v['is']['!']) && $v['is']['!']) {
        $aa['readonly'] = true;
    }
    if (isset($v['expand']) || (isset($v['type']) && strpos(',button,reset,submit,', ',' . $v['type'] . ',') !== false && !isset($v['expand']))) {
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
                foreach ($v['values'] as $ii => $vv) {
                    if (!isset($vv)) continue;
                    if ($vv && is_string($vv) && $vv[0] === '<' && strpos($vv, '</') !== false && substr($vv, -1) === '>') {
                        $html .= $vv;
                    } else {
                        $html .= call_user_func('Form::' . $type, $k, $ii, $vv, array_replace_recursive(['classes' => ['button', 'f-' . $kk . ':' . $ii], 'id' => 'f-' . $kk . ':' . $ii], $aa)) . ' ';
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
        } else if (strpos(',color,date,email,number,password,search,tel,text,url,', ',' . $type . ',') !== false) {
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

function __panel_s__($k, $v, $i = "") {
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
                    if (is_object($vv)) {
                        $html .= '<li>' . HTML::a($v['content'][1][$kk]->title, $vv->url . $i) . '</li>';
                    } else {
                        $html .= '<li>' . HTML::a($v['content'][1][$kk]['title'], $vv['url'] . $i) . '</li>';
                    }
                }
            }
            if (isset($v['a']) && $v['a']) {
                $a = [];
                foreach ($v['a'] as $kk => $vv) {
                    if (!isset($vv)) continue;
                    $a[] = stripos($vv, '</a>') !== false ? $vv : HTML::a($kk, $vv);
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