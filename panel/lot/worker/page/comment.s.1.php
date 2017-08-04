<?php echo __panel_s__('source', [
    'list' => [[$__source[0]], [$__source[1]]],
    'if' => $__source[0]
]); ?>
<?php echo __panel_s__('kin', [
    'list' => $__kins,
    'a' => [
        ['&#x2795;', $__state->path . '/::s::/' . (Path::D($__path) ?: $__path), false, ['title' => $language->add]],
        $__is_has_step_kin ? ['&#x22EF;', $__state->path . '/::g::/' . Path::D($__path) . '/2', false, ['title' => $language->more]] : null
    ],
    'if' => $__kins[0]
]); ?>
<?php

$__parents = [];
$__s = end($__chops);
if ($__g = g(LOT . DS . Path::D($__path), 'draft,page,archive')) {
    if (count($__g) > $__state->chunk) {
        echo __panel_s__('parent', [
            'title' => $language->parent,
            'content' => '<p>' . Form::text('parent', $__page[0]->parent, $__page[0]->parent, ['classes' => ['input', 'block']]) . '</p>'
        ]);
    } else {
        foreach ($__g as $__v) {
            $__vv = new Date(Path::N($__v));
            $__parents[$__vv->slug] = HTML::a($__vv->F2, $__state->path . '/::g::/' . $__chops[0] . '/' . ltrim(To::url(Path::F($__v, COMMENT)),'/'), true);
        }
        if ($__page[0]->parent) {
            $__parents[""] = '<span title="' . $language->none . '">&#x2716;</span>';
        }
        echo __panel_s__('parent', [
            'title' => $language->parent,
            'content' => '<div>' . Form::radio('parent', $__parents, (new Date($__page[0]->parent))->slug, ['classes' => ['input']]) . '</div>'
        ]);
    }
}

?>