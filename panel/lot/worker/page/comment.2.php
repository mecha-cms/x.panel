<?php echo __panel_s__('source', [
    'content' => [[$__source[0]], [$__source[1]]],
    'if' => $__source[0]
]); ?>
<?php echo __panel_s__('kin', [
    'content' => $__kins,
    'a' => [
        HTML::a('&#x2795;', $__state->path . '/::s::/' . (Path::D($__path) ?: $__path), false, ['title' => $language->add]),
        $__is_has_step_kin ? HTML::a('&#x22EF;', $__state->path . '/::g::/' . Path::D($__path) . '/2', false, ['title' => $language->more]) : null
    ],
    'if' => $__kins[0]
]); ?>
<?php echo __panel_s__('child', [
    'content' => $__childs,
    'a' => [
        HTML::a('&#x2795;', $__state->path . '/::s::/' . Path::D($__path), false, ['title' => $language->add]),
        $__is_has_step_child ? HTML::a('&#x22EF;', $__state->path . '/::g::/' . Path::D($__path) . '/2', false, ['title' => $language->more]) : null
    ]
]); ?>