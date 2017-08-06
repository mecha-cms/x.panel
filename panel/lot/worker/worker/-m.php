<?php

// tab
$__hiddens = [];
$__submit = false;
if ($__t = array_filter(a(Config::get('panel.m.t', [])), function($__v) {
    return isset($__v) && isset($__v['stack']) && is_numeric($__v['stack']);
})) {
    // before tab
    if (!empty($__f = Config::get('panel.m.before'))) {
        if (is_string($__f) && is_file($__f)) {
            require $__f;
        } else {
            echo $__f;
        }
    }
    if (count($__t) > 1) {
        $__t = Anemon::eat($__t)->sort([1, 'stack'], true)->vomit();
        echo '<nav class="t">';
        $__tt = array_keys($__t);
        $__tt = array_shift($__tt);
        foreach ($__t as $__k => $__v) {
            if (!isset($__v['title'])) {
                $__v['title'] = $language->{$__k};
            }
            echo HTML::a($__v['title'], '#t:' . $__k, false, ['class' => Request::get('m.t:v', Config::get('panel.m.t:v', $__tt)) === $__k ? 'is.active' : null]);
        }
        echo '</nav>';
    }
    foreach ($__t as $__k => $__v) {
        if (!isset($__v['title'])) {
            $__v['title'] = $language->{$__k};
        }
        echo '<section class="t-c t-c:' . $__k . '" id="t:' . $__k . '">';
        if (!isset($__v['list']) && $__w = File::exist(__DIR__ . DS . '..' . DS . 'page' . DS . $__chops[0] . '.m.t.' . $__k . '.php')) {
            $__v['list'] = include $__w;
        } else if (isset($__v['list']) && is_string($__v['list']) && is_file($__v['list'])) {
            $__v['list'] = include $__v['list'];
        }
        if (!empty($__v['list'])) {
            echo '<fieldset>';
            if (!isset($__v['legend']) || $__v['legend'] !== false) {
                echo '<legend>' . (isset($__v['legend']) ? $__v['legend'] : $__v['title']) . '</legend>';
                if (is_array($__v['list'])) {
                    if ($__a = a(Config::get('panel.f.' . $__k, []))) {
                        $__v['list'] = array_replace_recursive($__v['list'], (array) $__a);
                    }
                    foreach (Anemon::eat($__v['list'])->is(function($__v) {
                        return isset($__v) && isset($__v['stack']) && is_numeric($__v['stack']);
                    })->sort([1, 'stack'], "")->vomit() as $__kk => $__vv) {
                        if (isset($__vv['type'])) {
                            if (strpos(X . 'submit' . X . 'submit[]' . X, X . $__vv['type'] . X) !== false) {
                                $__submit = [$__kk, $__vv];
                                continue;
                            } else if ($__vv['type'] === 'hidden') {
                                $__hiddens[$__kk] = $__vv;
                                continue;
                            }
                        }
                        echo __panel_f__($__kk, $__vv);
                    }
                } else if (is_string($__v['list'])) {
                    echo $__v['list'];
                }
            }
            echo '</fieldset>';
            if (!empty($__v['description'])) {
                $__s = $__v['description'];
                echo '<div class="h p">' . (stripos($__s, '</p>') === false ? '<p>' . $__s . '</p>' : $__s) . '</div>';
            }
        } else {
            if (!isset($__v['content']) && $__w = File::exist(__DIR__ . DS . '..' . DS . 'page' . DS . $__chops[0] . '.m.t.' . $__k . '.php')) {
                $__v['content'] = include $__w;
            } else if (isset($__v['content']) && is_string($__v['content']) && is_file($__v['content'])) {
                $__v['content'] = include $__v['content'];
            }
            if (!empty($__v['content'])) {
                echo $__v['content'];
            }
        }
        echo '</section>';
    }
    // after tab
    if (!empty($__f = Config::get('panel.m.after'))) {
        if (is_string($__f) && is_file($__f)) {
            require $__f;
        } else {
            echo $__f;
        }
    }
    if (Config::get('panel.c:f') || Config::get('panel.m:f')) {
        if (isset($__submit) && $__submit) {
            echo call_user_func_array('__panel_f__', $__submit);
        } else {
            echo __panel_f__('x', [
                'type' => 'submit',
                'title' => $language->submit,
                'value' => 'txt'
            ]);
        }
        if (!empty($__hiddens)) {
            foreach ($__hiddens as $__k => $__v) {
                echo __panel_f__($__k, $__v);
            }
        }
    }
} else {
    echo '<p>:(</p>';
}

// content
if (!empty($__f = Config::get('panel.m.content'))) {
    if (is_string($__f) && is_file($__f)) {
        require $__f;
    } else {
        echo $__f;
    }
}